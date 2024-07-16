<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\Admin\BrandCreateForm;
use App\Livewire\Forms\Admin\BrandEditForm;
use App\Models\Brand;
use App\Models\Product;
use Error;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Throwable;

class Brands extends Component
{
  use WithFileUploads;
  use WithPagination;
  public $page;
  public function updatedPage()
  {
    $this->dispatch("refresh-flowbite");
  }

  public BrandCreateForm $createForm;
  public BrandEditForm $editForm;

  public $sortDir = "";
  public $sortBy = "";
  public $keyword = "";
  public function updatedKeyword()
  {
    $this->dispatch("refresh-flowbite");
  }
  public $perPage = 10;
  public function updatedPerPage()
  {
    $this->dispatch("refresh-flowbite");
  }
  public $withTrashed = false;
  public $onlyTrashed = false;
  public function updatedWithTrashed()
  {
    $this->withTrashed == true ? $this->onlyTrashed = false : "";
    $this->dispatch("refresh-flowbite");

  }
  public function updatedOnlyTrashed()
  {
    $this->onlyTrashed == true ? $this->withTrashed = false : "";
    $this->dispatch("refresh-flowbite");
  }

  #[Computed()]
  public function brandsTemplate()
  {
    return Brand::search($this->keyword)
      ->when($this->sortBy && $this->sortDir, function ($query) {
        return $query->orderBy($this->sortBy, $this->sortDir);
      })
      ->when($this->withTrashed == true, function ($query) {
        $query->withTrashed();
      })
      ->when($this->onlyTrashed == true, function ($query) {
        $query->onlyTrashed();
      });
  }

  #[Computed()]
  public function brands()
  {
    return $this->brandsTemplate->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  #[Computed()]
  public function columns()
  {
    return [
      "name" => "Brand",
      "slug" => "Slug",
      "updated_at" => "Last Update"
    ];
  }

  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
    $this->dispatch("refresh-flowbite");
  }

  public $selectedBrand;
  public function showViewModal($id)
  {
    $this->selectedBrand = Brand::withTrashed()->find($id);
    $this->dispatch("open-brand-view-modal");
  }

  public function showEditModal($id)
  {
    $brand = Brand::withTrashed()->find($id);
    $this->selectedBrand = $brand;
    $this->editForm->name = $brand->name;
    $this->editForm->slug = $brand->slug;
    $this->editForm->brandId = $brand->id;
    $this->dispatch("open-brand-edit-modal");
  }

  public function updatedCreateFormName()
  {
    $this->createForm->slug = Str::slug($this->createForm->name);
  }

  public function updatedEditFormName()
  {
    $this->editForm->slug = Str::slug($this->editForm->name);
  }

  public function create()
  {
    try {
      if (!Gate::allows("create brands")) {
        throw new UnauthorizedException("can not create brand");
      }
    } catch (UnauthorizedException $e) {
      return $this->dispatch("unauthorized-action");
    }

    $this->createForm->validate();

    try {
      $imageName = $this->createForm->image->store("brand_images", "public");

      Brand::create([
        "name" => Str::headline($this->createForm->name),
        "slug" => $this->createForm->slug,
        "image" => $imageName,
        "created_by" => auth()->user()->id,
        "created_at" => Carbon::now(),
      ]);

      $this->dispatch("close-brand-create-modal");
      $this->resetCreateFormFields();
      $this->dispatch("create_brand_success");
      $this->dispatch("refresh-flowbite");

    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function resetCreateFormFields()
  {
    $this->createForm->reset();
    $this->createForm->resetErrorBag();
  }

  public function update()
  {
    try {
      if (!Gate::allows("edit brands")) {
        throw new UnauthorizedException("can not edit brand");
      }

      $this->editForm->validate();

      if ($this->editForm->image) {
        $imageName = $this->editForm->image->store("category_images", "public");
        if (Storage::disk("public")->exists($this->selectedCategory->image)) {
          Storage::disk("public")->delete($this->selectedCategory->image);
        }
      }

      $this->selectedBrand->update([
        "name" => $this->editForm->name,
        "slug" => $this->editForm->slug,
        "image" => $imageName ?? $this->selectedBrand->image,
        "updated_by" => auth()->user()->id,
        "updated_at" => Carbon::now(),
      ]);

      $this->dispatch("close-brand-edit-modal");
      $this->dispatch("update_brand_success");
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  #[On("delete-brand-modal-is-confirmed")]
  public function delete($brandId)
  {
    /* $brand = Brand::find($brandId);
    $brand->delete();
    return; */
    try {
      if (!Gate::allows("delete brands")) {
        throw new UnauthorizedException("can not delete brand");
      }
      $brand = Brand::findOrFail($brandId);
      $relatedProductsCount = Product::where("brand_id", $brand->id)->count();
      if ($relatedProductsCount) {
        $this->dispatch("delete_brand_error", title: "There are associated products with this brand.", text: "Either reassign the products to a different brand or delete the products before deleting the brand.");
      } else {
        $brand->delete();
        $brand->update([
          "deleted_by" => auth()->user()->id
        ]);
        $this->dispatch("delete_brand_success");
      }
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Brand not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  #[On("force-delete-brand-modal-is-confirmed")]
  public function forceDelete($brandId)
  {
    // $this->authorize("force delete categories");
    try {
      if (!Gate::allows("force delete brands")) {
        throw new UnauthorizedException("you cant delete brand permanently");
      }
      $brand = Brand::withTrashed()->find($brandId);
      $brand->forceDelete();
      $this->dispatch("force-delete_brand_success");
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function restore($brandId)
  {
    try {
      if (!Gate::allows("force delete brands")) {
        throw new UnauthorizedException("you cant restore brand");
      }
      Brand::withTrashed()->find($brandId)->restore();
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }


  public function render()
  {
    return view('livewire.admin.brands')->layout("components.admin-layout", ['title' => "Brands"])->section("content");
  }
}
