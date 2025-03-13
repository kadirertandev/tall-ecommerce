<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\Admin\BrandCreateForm;
use App\Livewire\Forms\Admin\BrandEditForm;
use App\Models\Brand;
use App\Models\Product;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithTryCatch;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Brands extends Component
{
  use WithFileUploads;
  use WithPagination;
  use WithTryCatch;
  use WithRefreshFlowbite;

  public BrandCreateForm $createForm;
  public BrandEditForm $editForm;

  public function boot()
  {
    $this->refreshFlobwite();
  }

  public $sortDir = "";
  public $sortBy = "";
  public $keyword = "";
  public $perPage = 10;
  public $columns = [
    "name" => "Brand",
    "slug" => "Slug",
    "updated_at" => "Last Update"
  ];

  #[Url()]
  public $withTrashed = false;
  public function updatedWithTrashed()
  {
    if ($this->withTrashed == true)
      $this->onlyTrashed = false;
  }

  #[Url()]
  public $onlyTrashed = false;
  public function updatedOnlyTrashed()
  {
    if ($this->onlyTrashed == true)
      $this->withTrashed = false;
  }

  public function updatedCreateFormName()
  {
    $this->createForm->slug = Str::slug($this->createForm->name);
  }

  public function updatedEditFormName()
  {
    $this->editForm->slug = Str::slug($this->editForm->name);
  }

  public function resetCreateFormFields()
  {
    $this->createForm->reset();
    $this->createForm->resetErrorBag();
  }

  #[Computed()]
  public function brands()
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
      })
      ->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
  }

  public $selectedBrand;
  public function showViewModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->selectedBrand = Brand::withTrashed()->findOrFail($id);

      $this->dispatch("open-brand-view-modal");
    });
  }

  public function showEditModal($id)
  {
    $this->tryCatch(function () use ($id) {
      if (!Gate::allows("edit brands")) {
        throw new UnauthorizedException("can not edit brand");
      }

      $brand = Brand::findOrFail($id);

      $this->selectedBrand = $brand;
      $this->editForm->name = $brand->name;
      $this->editForm->slug = $brand->slug;
      $this->editForm->brandId = $brand->id;

      $this->dispatch("open-brand-edit-modal");
    });
  }

  public function create()
  {
    $this->createForm->validate();

    $this->tryCatch(function () {
      if (!Gate::allows("create brands")) {
        throw new UnauthorizedException("can not create brand");
      }

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
    });
  }

  public function update()
  {
    $this->editForm->validate();

    $this->tryCatch(function () {
      if (Gate::allows("edit brands")) {
        throw new UnauthorizedException("can not edit brand");
      }

      $brand = Brand::findOrFail($this->selectedBrand->id);

      if ($this->editForm->image) {
        $imageName = $this->editForm->image->store("category_images", "public");
        if (Storage::disk("public")->exists($this->selectedCategory->image)) {
          Storage::disk("public")->delete($this->selectedCategory->image);
        }
      }

      $brand->update([
        "name" => $this->editForm->name,
        "slug" => $this->editForm->slug,
        "image" => $imageName ?? $brand->image,
        "updated_by" => auth()->user()->id,
        "updated_at" => Carbon::now(),
      ]);

      $this->dispatch("close-brand-edit-modal");
      $this->dispatch("update_brand_success");
    });
  }

  #[On("delete-brand-modal-is-confirmed")]
  public function delete($brandId)
  {
    $this->tryCatch(function () use ($brandId) {
      if (!Gate::allows("delete brands")) {
        throw new UnauthorizedException("can not delete brand");
      }

      $brand = Brand::findOrFail($brandId);

      $hasRelatedProducts = Product::where("brand_id", $brand->id)->exists();

      if ($hasRelatedProducts) {
        $this->dispatch("delete_brand_error", title: "There are associated products with this brand.", text: "Either reassign the products to a different brand or delete the products before deleting the brand.");
      } else {
        $brand->delete();
        $brand->update([
          "deleted_by" => auth()->user()->id
        ]);

        $this->dispatch("delete_brand_success");
      }
    });
  }

  #[On("force-delete-brand-modal-is-confirmed")]
  public function forceDelete($brandId)
  {
    $this->tryCatch(function () use ($brandId) {
      if (!Gate::allows("force delete brands")) {
        throw new UnauthorizedException("you cant delete brand permanently");
      }

      $brand = Brand::withTrashed()->findOrFail($brandId);
      $brand->forceDelete();

      $this->dispatch("force-delete_brand_success");
    });
  }

  public function restore($brandId)
  {
    $this->tryCatch(function () use ($brandId) {
      if (!Gate::allows("force delete brands")) {
        throw new UnauthorizedException("you cant restore brand");
      }

      Brand::withTrashed()->findOrFail($brandId)->restore();
    });
  }

  public function render()
  {
    return view('livewire.admin.brands')
      ->layout("components.admin-layout", ['title' => "Brands"])
      ->section("content");
  }
}
