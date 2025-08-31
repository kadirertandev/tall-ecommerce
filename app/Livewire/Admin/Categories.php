<?php

namespace App\Livewire\Admin;

use App\Jobs\InsertItemToCategoryLanguageFiles;
use App\Jobs\RemoveItemFromCategoryLanguageFiles;
use App\Jobs\UpdateCategoryLanguageFiles;
use App\Traits\WithInteractModal;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithRemoveFormImage;
use App\Traits\WithSweetAlert;
use App\Traits\WithTableSortAndFilter;
use App\Traits\WithSoftDeleteFilter;
use App\Traits\WithTryCatch;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Forms\Admin\CategoryEditForm;
use App\Livewire\Forms\Admin\CategoryCreateForm;
use App\Traits\WithUpdateFormSlug;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Categories extends Component
{
  use WithFileUploads;
  use WithPagination;
  use WithTryCatch;
  use WithRefreshFlowbite;
  use WithSweetAlert;
  use WithInteractModal;
  use WithSoftDeleteFilter;
  use WithRemoveFormImage;
  use WithTableSortAndFilter;
  use WithUpdateFormSlug;

  public CategoryCreateForm $createForm;
  public CategoryEditForm $editForm;

  public function mount()
  {
    $this->validateOrderByInputs();
  }

  public function boot()
  {
    $this->refreshFlobwite();
  }

  public $onlyPopular = false;

  public $columns = [
    "name" => "Category",
    "is_popular" => "Is Popular",
    "updated_at" => "Last Update"
  ];

  #[Computed()]
  public function allowedColumns()
  {
    return array_keys($this->columns);
  }

  #[Computed()]
  public function categories()
  {
    return Category::search($this->keyword)
      ->withoutColumns(["slug", "created_by", "updated_by", "created_at", "deleted_by"])
      ->sortByColumn($this->orderByColumn, $this->orderByDirection)
      ->filterByTrashed($this->withTrashed, $this->onlyTrashed)
      ->when($this->onlyPopular == true, function ($query) {
        $query->where("is_popular", true);
      })
      ->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  public $selectedCategory;

  public function showViewModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->selectedCategory = Category::withTrashed()->findOrFail($id);

      $this->showModal("view-category");
    });
  }

  public function showEditModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->authorize("edit categories");

      $category = Category::findOrFail($id);

      $this->selectedCategory = $category;
      $this->editForm->name = $category->name;
      $this->editForm->slug = $category->slug;
      $this->editForm->is_popular = $category->is_popular;
      $this->editForm->categoryId = $category->id;
      $this->editForm->categoryBrands = $category->brands->pluck("id")->toArray();

      $this->showModal("edit-category");
    });
  }

  public function afterModalClosed($modalName)
  {
    if ($modalName == "view-category") {
      $this->reset("selectedCategory");
    }
  }

  public function updateCategoryIsPopular($checked = null)
  {
    $this->tryCatch(function () use ($checked) {
      $this->authorize("edit categories");

      $category = Category::findOrFail($this->selectedCategory->id);

      $category->update(["is_popular" => $checked === true ? 1 : 0]);
    });
  }

  public function updateCategoryBrands($brandId = null, $checked = null)
  {
    $this->tryCatch(function () use ($brandId, $checked) {
      $this->authorize("edit categories");

      $category = Category::findOrFail($this->selectedCategory->id);

      if ($checked == true) {
        !$category->brands->contains($brandId) ? $category->brands()->attach($brandId) : null;
      } else {
        $category->brands->contains($brandId) ? $category->brands()->detach($brandId) : null;
      }
    });
  }

  public function create()
  {
    $this->createForm->validate();

    $this->tryCatch(function () {
      $this->authorize("create categories");

      $imageName = $this->createForm->image->store("category_images", "public");

      Category::create([
        "name" => Str::headline($this->createForm->name),
        "slug" => $this->createForm->slug,
        "image" => $imageName,
        "is_popular" => $this->createForm->is_popular ? 1 : 0,
        "created_by" => auth()->user()->id,
        "created_at" => Carbon::now(),
      ]);

      dispatch(new InsertItemToCategoryLanguageFiles($this->createForm->name, $this->createForm->slug));

      $this->closeModal("create-category");

      $this->swalToast([
        "titleText" => "Category created successfully!"
      ]);
    });
  }

  public function update()
  {
    $this->editForm->validate();

    $this->tryCatch(function () {
      $this->authorize("edit categories");

      $category = Category::findOrFail($this->selectedCategory->id);
      $oldSlug = $category->slug;

      if ($this->editForm->image) {
        $imageName = $this->editForm->image->store("category_images", "public");
        if (Storage::disk("public")->exists($category->image)) {
          Storage::disk("public")->delete($category->image);
        }
      }

      $category->update([
        "name" => $this->editForm->name,
        "slug" => $this->editForm->slug,
        "image" => $imageName ?? $category->image,
        "updated_by" => auth()->user()->id,
        "updated_at" => Carbon::now(),
      ]);

      $this->selectedCategory = $category;

      dispatch(new UpdateCategoryLanguageFiles($oldSlug, $this->editForm->slug, $this->editForm->name));

      $this->closeModal("edit-category");

      $this->swalToast([
        "titleText" => "Category updated successfully!"
      ]);
    });
  }

  public function askDeleteCategory($categoryId, $permanently = false)
  {
    $this->swalQuestion([
      "titleText" => "Are you sure you want to delete this category " . ($permanently ? "permanently?" : "?"),
      "confirmButtonText" => 'Yes',
      "denyButtonText" => "No",
      "onConfirm" => (!$permanently ? "" : "force-") . "delete-category-confirmed",
      "onConfirmParameters" => [
        "categoryId" => $categoryId
      ],
      "customClass" => [
        "title" => "text-nowrap!",
        "popup" => "min-w-max!"
      ]
    ]);
  }

  #[On("delete-category-confirmed")]
  public function delete($categoryId)
  {
    $this->tryCatch(function () use ($categoryId) {
      $this->authorize("delete categories");

      $category = Category::findOrFail($categoryId);

      $relatedProductsCount = Product::where("category_id", $category->id)->exists();

      if ($relatedProductsCount) {
        $this->swalTemplateAssociatedExistsError([
          "titleText" => "There are associated products with this category.",
          "text" => "Either reassign the products to a different category or delete the products before deleting the category."
        ]);
      } else {
        $category->delete();
        $category->update([
          "deleted_by" => auth()->user()->id
        ]);

        $this->swalToast([
          "titleText" => "Category deleted successfully!"
        ]);
      }
    });
  }

  #[On("force-delete-category-confirmed")]
  public function forceDelete($categoryId)
  {
    $this->tryCatch(function () use ($categoryId) {
      $this->authorize("force delete categories");

      $category = Category::withTrashed()->findOrFail($categoryId);
      $category->forceDelete();

      dispatch(new RemoveItemFromCategoryLanguageFiles($category->slug));

      $this->swalToast([
        "titleText" => "Category deleted permanently successfully!"
      ]);
    });
  }

  public function restore($categoryId)
  {
    $this->tryCatch(function () use ($categoryId) {
      $this->authorize("force delete categories");

      $category = Category::withTrashed()->findOrFail($categoryId);
      $category->restore();
      $category->update([
        "deleted_by" => null
      ]);

      $this->swalToast([
        "titleText" => "Category restored successfully!"
      ]);
    });
  }

  public function render()
  {
    return view('livewire.admin.categories')
      ->layout("components.admin-layout", ["title" => "Categories"])
      ->section("content");
  }
}
