<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\Admin\BrandCreateForm;
use App\Livewire\Forms\Admin\BrandEditForm;
use App\Models\Brand;
use App\Models\Product;
use App\Traits\WithRemoveFormImage;
use App\Traits\WithTableSortAndFilter;
use App\Traits\WithSoftDeleteFilter;
use App\Traits\WithInteractModal;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithTryCatch;
use App\Traits\WithUpdateFormSlug;
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

class Brands extends Component
{
  use WithFileUploads;
  use WithPagination;
  use WithTryCatch;
  use WithRefreshFlowbite;
  use WithInteractModal;
  use WithSoftDeleteFilter;
  use WithRemoveFormImage;
  use WithTableSortAndFilter;
  use WithUpdateFormSlug;

  public BrandCreateForm $createForm;
  public BrandEditForm $editForm;

  public function boot()
  {
    $this->refreshFlobwite();
  }

  public $columns = [
    "name" => "Brand",
    "is_popular" => "Is Popular",
    "updated_at" => "Last Update"
  ];

  #[Computed()]
  public function brands()
  {
    return Brand::search($this->keyword)
      ->withoutColumns(["slug", "created_by", "updated_by", "created_at", "deleted_by"])
      ->sortByColumn($this->sortBy, $this->sortDir)
      ->filterByTrashed($this->withTrashed, $this->onlyTrashed)
      ->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  public $selectedBrand;
  public function showViewModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->selectedBrand = Brand::withTrashed()->findOrFail($id);

      $this->showModal("view-brand");
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

      $this->showModal("edit-brand");
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

      $this->closeModal("create-brand");

      $this->swalToast([
        "titleText" => "Brand created successfully!"
      ]);
    });
  }

  public function update()
  {
    $this->editForm->validate();

    $this->tryCatch(function () {
      if (!Gate::allows("edit brands")) {
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

      $this->selectedBrand = $brand;

      $this->closeModal("edit-brand");

      $this->swalToast([
        "titleText" => "Brand updated successfully!"
      ]);
    });
  }

  public function askDeleteBrand($brandId, $permanently = false)
  {
    $this->swalQuestion([
      "titleText" => "Are you sure you want to delete this brand " . ($permanently ? "permanently?" : "?"),
      "confirmButtonText" => 'Yes',
      "denyButtonText" => "No",
      "onConfirm" => (!$permanently ? "" : "force-") . "delete-brand-confirmed",
      "onConfirmParameters" => [
        "brandId" => $brandId
      ],
      "customClass" => [
        "title" => "text-nowrap!",
        "popup" => "min-w-max!"
      ]
    ]);
  }

  #[On("delete-brand-confirmed")]
  public function delete($brandId)
  {
    $this->tryCatch(function () use ($brandId) {
      if (!Gate::allows("delete brands")) {
        throw new UnauthorizedException("can not delete brand");
      }

      $brand = Brand::findOrFail($brandId);

      $hasRelatedProducts = Product::where("brand_id", $brand->id)->exists();

      if ($hasRelatedProducts) {
        $this->swalTemplateAssociatedExistsError([
          "titleText" => "There are associated products with this brand.",
          "text" => "Either reassign the products to a different brand or delete the products before deleting the brand."
        ]);
      } else {
        $brand->delete();
        $brand->update([
          "deleted_by" => auth()->user()->id
        ]);

        $this->swalToast([
          "titleText" => "Brand deleted successfully!"
        ]);
      }
    });
  }

  #[On("force-delete-brand-confirmed")]
  public function forceDelete($brandId)
  {
    $this->tryCatch(function () use ($brandId) {
      if (!Gate::allows("force delete brands")) {
        throw new UnauthorizedException("you cant delete brand permanently");
      }

      $brand = Brand::withTrashed()->findOrFail($brandId);
      $brand->forceDelete();

      $this->swalToast([
        "titleText" => "Brand deleted permanently successfully!"
      ]);
    });
  }

  public function restore($brandId)
  {
    $this->tryCatch(function () use ($brandId) {
      if (!Gate::allows("force delete brands")) {
        throw new UnauthorizedException("you cant restore brand");
      }

      Brand::withTrashed()->findOrFail($brandId)->restore();

      $this->swalToast([
        "titleText" => "Brand restored successfully!"
      ]);
    });
  }

  public function render()
  {
    return view('livewire.admin.brands')
      ->layout("components.admin-layout", ['title' => "Brands"])
      ->section("content");
  }
}
