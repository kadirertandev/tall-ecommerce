<?php

namespace App\Livewire\Admin;

use App\Jobs\InsertItemToCategoryLanguageFiles;
use App\Jobs\RemoveItemFromCategoryLanguageFiles;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithTryCatch;
use App\Models\Product;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Livewire\Forms\Admin\CategoryEditForm;
use App\Livewire\Forms\Admin\CategoryCreateForm;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Categories extends Component
{
  use WithFileUploads;
  use WithPagination;
  use WithTryCatch;
  use WithRefreshFlowbite;

  public CategoryCreateForm $createForm;
  public CategoryEditForm $editForm;

  public function boot()
  {
    $this->refreshFlobwite();
  }

  public $sortDir = "";
  public $sortBy = "";
  public $keyword = "";
  public $perPage = 10;
  public $onlyPopular = false;
  public $columns = [
    "name" => "Category",
    "is_popular" => "Is Popular",
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

  public function updatedEditFormName()
  {
    $this->editForm->slug = Str::slug($this->editForm->name);
  }

  public function updatedCreateFormName()
  {
    $this->createForm->slug = Str::slug($this->createForm->name);
  }

  public function removeImage()
  {
    $this->editForm->reset("image");
    $this->editForm->resetErrorBag("image");
    $this->createForm->reset("image");
    $this->createForm->resetErrorBag("image");
  }

  public function resetCreateFormFields()
  {
    $this->createForm->reset();
    $this->createForm->resetErrorBag();
  }

  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
  }

  #[Computed()]
  public function categories()
  {
    return Category::search($this->keyword)
      ->when($this->sortBy && $this->sortDir, function ($query) {
        return $query->orderBy($this->sortBy, $this->sortDir);
      })
      ->when($this->withTrashed == true, function ($query) {
        $query->withTrashed();
      })
      ->when($this->onlyTrashed == true, function ($query) {
        $query->onlyTrashed();
      })
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

      $this->dispatch("open-category-view-modal");
    });
  }

  public function showEditModal($id)
  {
    $this->tryCatch(function () use ($id) {
      if (!Gate::allows("edit categories")) {
        throw new UnauthorizedException("can not edit category");
      }

      $category = Category::findOrFail($id);

      $this->selectedCategory = $category;
      $this->editForm->name = $category->name;
      $this->editForm->slug = $category->slug;
      $this->editForm->is_popular = $category->is_popular;
      $this->editForm->categoryId = $category->id;
      $this->editForm->categoryBrands = $category->brands->pluck("id")->toArray();

      $this->dispatch("open-category-edit-modal");
    });
  }

  public function updateCategoryIsPopular($checked = null)
  {
    $this->tryCatch(function () use ($checked) {
      $category = Category::findOrFail($this->selectedCategory->id);

      $category->update(["is_popular" => $checked === true ? 1 : 0]);
    });
  }

  public function updateCategoryBrands($brandId = null, $checked = null)
  {
    $this->tryCatch(function () use ($brandId, $checked) {
      $category = Category::findOrFail($this->selectedCategory->id);

      if ($checked == true) {
        !$category->brands->contains($brandId) ? $category->brands()->attach($brandId) : null;
      } else {
        $category->brands->contains($brandId) ? $category->brands()->detach($brandId) : null;
      }
    });
  }

  public function update()
  {
    $this->editForm->validate();

    $this->tryCatch(function () {
      if (!Gate::allows("edit categories")) {
        throw new UnauthorizedException("can not edit category");
      }

      $category = Category::findOrFail($this->selectedCategory->id);

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

      $this->dispatch("close-category-edit-modal");
      $this->dispatch("update_category_success");
    });
  }

  public function create()
  {
    $this->createForm->validate();

    $this->tryCatch(function () {
      if (!Gate::allows("create categories")) {
        throw new UnauthorizedException("can not create category");
      }

      $imageName = $this->createForm->image->store("category_images", "public");

      Category::create([
        "name" => Str::headline($this->createForm->name),
        "slug" => $this->createForm->slug,
        "image" => $imageName,
        "is_popular" => $this->createForm->is_popular ? 1 : 0,
        "created_by" => auth()->user()->id,
        "created_at" => Carbon::now(),
      ]);

      dispatch(new InsertItemToCategoryLanguageFiles($this->createForm->name));

      $this->dispatch("close-category-create-modal");
      $this->resetCreateFormFields();
      $this->dispatch("create_category_success");
    });
  }

  #[On("delete-category-modal-is-confirmed")]
  public function delete($categoryId)
  {
    $this->tryCatch(function () use ($categoryId) {
      if (!Gate::allows("delete categories")) {
        throw new UnauthorizedException("can not delete category");
      }

      $category = Category::findOrFail($categoryId);

      $relatedProductsCount = Product::where("category_id", $category->id)->exists();

      if ($relatedProductsCount) {
        $this->dispatch("delete_category_error", title: "There are associated products with this category.", text: "Either reassign the products to a different category or delete the products before deleting the category.");
      } else {
        $category->delete();
        $category->update([
          "deleted_by" => auth()->user()->id
        ]);

        $this->dispatch("delete_category_success");
      }
    });
  }

  #[On("force-delete-category-modal-is-confirmed")]
  public function forceDelete($categoryId)
  {
    $this->tryCatch(function () use ($categoryId) {
      if (!Gate::allows("force delete categories")) {
        throw new UnauthorizedException("you cant delete category permanently");
      }

      $category = Category::withTrashed()->findOrFail($categoryId);
      $category->forceDelete();

      dispatch(new RemoveItemFromCategoryLanguageFiles($category->slug));

      $this->dispatch("force-delete_category_success");
    });
  }
  public function restore($categoryId)
  {
    $this->tryCatch(function () use ($categoryId) {
      if (!Gate::allows("force delete categories")) {
        throw new UnauthorizedException("you cant restore category");
      }

      Category::withTrashed()->findOrFail($categoryId)->restore();
    });
  }

  #[On("create_category_success")]
  public function render()
  {
    return view('livewire.admin.categories')
      ->layout("components.admin-layout", ["title" => "Categories"])
      ->section("content");
  }
}
