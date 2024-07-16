<?php

namespace App\Livewire\Admin;

use App\Jobs\InsertItemToCategoryLanguageFiles;
use App\Jobs\RemoveItemFromCategoryLanguageFiles;
use App\Jobs\UpdateCategoryLanguageFiles;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Throwable;
use App\Models\Product;
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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Categories extends Component
{
  use WithFileUploads;
  use WithPagination;
  public $page;
  public function updatedPage()
  {
    $this->dispatch("refresh-flowbite");
  }

  public CategoryCreateForm $createForm;
  public CategoryEditForm $editForm;

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
  public $onlyPopular = false;
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
  public function categoriesTemplate()
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
      });
  }

  #[Computed()]
  public function categories()
  {
    return $this->categoriesTemplate->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  #[Computed()]
  public function columns()
  {
    return [
      "name" => "Category",
      "is_popular" => "Is Popular",
      "updated_at" => "Last Update"
    ];
  }

  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
    $this->dispatch("refresh-flowbite");
  }

  public $selectedCategory;
  public function showViewModal($id)
  {
    try {
      $this->selectedCategory = Category::withTrashed()->findOrFail($id);
      $this->dispatch("open-category-view-modal");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Category not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function showEditModal($id)
  {
    try {
      $category = Category::withTrashed()->findOrFail($id);
      $this->selectedCategory = $category;
      $this->editForm->name = $category->name;
      $this->editForm->slug = $category->slug;
      $this->editForm->is_popular = $category->is_popular;
      $this->editForm->categoryId = $category->id;
      $this->editForm->categoryBrands = $category->brands->pluck("id")->toArray();
      $this->dispatch("open-category-edit-modal");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Category not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
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

  public function updateCategoryIsPopular($checked = null)
  {
    // dd($checked);
    $this->selectedCategory->update(["is_popular" => $checked === true ? 1 : 0]);
  }

  public function updateCategoryBrands($brandId = null, $checked = null)
  {
    if ($checked == true) {
      !$this->selectedCategory->brands->contains($brandId) ? $this->selectedCategory->brands()->attach($brandId) : null;
    } else {
      $this->selectedCategory->brands->contains($brandId) ? $this->selectedCategory->brands()->detach($brandId) : null;
    }
  }

  public function update()
  {
    try {
      if (!Gate::allows("edit categories")) {
        throw new UnauthorizedException("can not edit category");
      }

      $this->editForm->validate();

      if ($this->editForm->image) {
        $imageName = $this->editForm->image->store("category_images", "public");
        if (Storage::disk("public")->exists($this->selectedCategory->image)) {
          Storage::disk("public")->delete($this->selectedCategory->image);
        }
      }

      $this->selectedCategory->update([
        "name" => $this->editForm->name,
        "slug" => $this->editForm->slug,
        "image" => $imageName ?? $this->selectedCategory->image,
        "updated_by" => auth()->user()->id,
        "updated_at" => Carbon::now(),
      ]);

      $this->dispatch("close-category-edit-modal");
      $this->dispatch("update_category_success");
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function create()
  {
    try {
      if (!Gate::allows("create categories")) {
        throw new UnauthorizedException("can not create category");
      }
    } catch (UnauthorizedException $e) {
      return $this->dispatch("unauthorized-action");
    }

    $this->createForm->validate();

    try {
      $imageName = $this->createForm->image->store("category_images", "public");

      Category::create([
        "name" => Str::headline($this->createForm->name),
        "slug" => $this->createForm->slug,
        "image" => $imageName,
        "is_popular" => $this->createForm->is_popular ? 1 : 0,
        "created_by" => auth()->user()->id,
        "created_at" => Carbon::now(),
      ]);

      // $this->putLanguageContent(); #switched to job
      // dispatch(new UpdateCategoryLanguageFiles($this->createForm->name));
      dispatch(new InsertItemToCategoryLanguageFiles($this->createForm->name));

      $this->dispatch("close-category-create-modal");
      $this->resetCreateFormFields();
      $this->dispatch("create_category_success");
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

  /**
   * Update category language files.
   */
  public function putLanguageContent()
  {
    $filePaths = ['en' => '../lang/en/categories.php', 'tr' => '../lang/tr/categories.php'];
    foreach ($filePaths as $lang => $filePath) {
      $content = include ($filePath);

      $categories = array_diff_key($content, array_flip(["dictionary"]));
      $dictionary = $content["dictionary"];

      $tr = new GoogleTranslate();
      $tr->setSource();

      if ($lang == "en") {
        $tr->setTarget("en");

        $name = Str::headline($tr->translate($this->createForm->name));
        $slug = Str::slug($name);

        $tr->setTarget("tr");
        $dictionarySlug = Str::slug($tr->translate($slug));

        $categories["{$dictionarySlug}"] = ["name" => "{$name}", "slug" => "{$slug}"];
        $dictionary["{$slug}"] = "{$dictionarySlug}";
      } else {
        $tr->setTarget("tr");

        $name = Str::headline($tr->translate($this->createForm->name));
        $slug = Str::slug($name);

        $tr->setTarget("en");
        $dictionarySlug = $tr->translate($slug);

        $categories["{$slug}"] = ["name" => "{$name}", "slug" => "{$slug}"];
        $dictionary["{$dictionarySlug}"] = "{$slug}";
      }

      $content = "<?php\n\nreturn [\n";
      foreach ($categories as $slug => $category) {
        $content .= "  \"$slug\" => [\n";
        $content .= "    \"name\" => \"" . $category['name'] . "\",\n";
        $content .= "    \"slug\" => \"" . $category['slug'] . "\"\n";
        $content .= "  ],\n";
      }

      $content .= "  \"dictionary\" => [\n";
      foreach ($dictionary as $en => $tr) {
        $content .= "    \"$en\" => \"" . $tr . "\",\n";
      }
      $content .= "  ]\n";

      $content .= "];\n";

      file_put_contents($filePath, $content);
    }
  }

  #[On("delete-category-modal-is-confirmed")]
  public function delete($categoryId)
  {
    try {
      if (!Gate::allows("delete categories")) {
        throw new UnauthorizedException("can not delete category");
      }
      $category = Category::findOrFail($categoryId);
      $relatedProductsCount = Product::where("category_id", $category->id)->count();
      if ($relatedProductsCount > 0) {
        $this->dispatch("delete_category_error", title: "There are associated products with this category.", text: "Either reassign the products to a different category or delete the products before deleting the category.");
      } else {
        $category->delete();
        $category->update([
          "deleted_by" => auth()->user()->id
        ]);
        $this->dispatch("delete_category_success");
      }

    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Category not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  #[On("force-delete-category-modal-is-confirmed")]
  public function forceDelete($categoryId)
  {
    // $this->authorize("force delete categories");
    try {
      if (!Gate::allows("force delete categories")) {
        throw new UnauthorizedException("you cant delete category permanently");
      }
      $category = Category::withTrashed()->findOrFail($categoryId);
      $category->forceDelete();
      dispatch(new RemoveItemFromCategoryLanguageFiles($category->slug));
      $this->dispatch("force-delete_category_success");
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Category not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function restore($categoryId)
  {
    try {
      if (!Gate::allows("force delete categories")) {
        throw new UnauthorizedException("you cant restore category");
      }
      Category::withTrashed()->findOrFail($categoryId)->restore();
    } catch (UnauthorizedException $e) {
      // dd($e->getMessage());
      $this->dispatch("unauthorized-action");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Category not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  #[On("create_category_success")]
  public function render()
  {
    return view('livewire.admin.categories')->layout("components.admin-layout", ["title" => "Categories"])->section("content");
  }
}
