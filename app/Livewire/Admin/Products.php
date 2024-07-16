<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\Admin\ProductCreateForm;
use App\Livewire\Forms\Admin\ProductEditForm;
use App\Models\Brand;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\DailyDealProduct;
use App\Models\Order;
use App\Models\Product;
use App\Models\WeeklyDealProduct;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;
use Throwable;

class Products extends Component
{
  use WithFileUploads;
  use WithPagination;
  public $page;
  public function updatedPage()
  {
    // $this->dispatch("admin-products-page-updated");
    $this->dispatch("refresh-flowbite");
  }

  public ProductCreateForm $createForm;
  public ProductEditForm $editForm;

  public $sortDir = "";
  public $sortBy = "";
  public $keyword = "";
  public function updatedKeyword()
  {
    $this->dispatch("refresh-flowbite");
  }
  public $categoriesFilter = [];
  public function updatedCategoriesFilter()
  {
    $this->dispatch("refresh-flowbite");
  }
  public $brandsFilter = [];
  public function updatedBrandsFilter()
  {
    $this->dispatch("refresh-flowbite");
  }
  public $minPrice;
  public $maxPrice;
  public $perPage = 10;
  public function updatedPerPage()
  {
    $this->dispatch("refresh-flowbite");
  }
  public $withTrashed = false;
  public $onlyTrashed = false;
  public function updatedWithTrashed()
  {
    // dd($this->withTrashed);
    $this->withTrashed == true ? $this->onlyTrashed = false : "";
    // $this->dispatch("with-trashed-updated");
    $this->dispatch("refresh-flowbite");

  }
  public function updatedOnlyTrashed()
  {
    // dd($this->onlyTrashed);
    $this->onlyTrashed == true ? $this->withTrashed = false : "";
    // $this->dispatch("only-trashed-updated");
    $this->dispatch("refresh-flowbite");

  }

  #[Computed()]
  public function productsTemplate()
  {
    $products = Product::search($this->keyword)
      ->when(
        $this->sortBy != "category" && $this->sortBy != "brand" && $this->sortBy != "rating" && $this->sortBy != "sales" && $this->sortBy != "revenue",
        function ($query) {
          // $query->orderBy($this->sortBy, $this->sortDir);
          $query->when($this->sortBy && $this->sortDir, function ($query) {
            return $query->orderBy($this->sortBy, $this->sortDir);
          });
        }
      )
      ->when($this->sortBy == "category", function ($query) {
        $query->join("categories", "products.category_id", "=", "categories.id")
          ->orderBy("categories.name", $this->sortDir)
          ->select("products.*");
      })
      ->when($this->sortBy == "brand", function ($query) {
        $query->join("brands", "products.brand_id", "=", "brands.id")
          ->orderBy("brands.name", $this->sortDir)
          ->select("products.*");
      })
      ->when($this->sortBy == "rating", function ($query) {
        $query->leftjoin("product_reviews", "products.id", "=", "product_reviews.product_id")
          ->select("products.*", DB::raw("avg(product_reviews.rating) as rating_average"))
          ->groupBy("products.id")
          ->orderBy("rating_average", $this->sortDir);
      })
      ->when($this->sortBy == "sales", function ($query) {
        $query->leftjoin("order_items", "products.id", "=", "order_items.product_id")
          ->select("products.*", DB::raw("sum(order_items.quantity) as total_sales"))
          ->groupBy("products.id")
          ->orderBy("total_sales", $this->sortDir);
      })
      ->when($this->sortBy == "revenue", function ($query) {
        $query->leftjoin("order_items", "products.id", "=", "order_items.product_id")
          ->select("products.*", DB::raw("sum(order_items.item_total_price) as revenue"))
          ->groupBy("products.id")
          ->orderBy("revenue", $this->sortDir);
      })
      ->when($this->withTrashed == true, function ($query) {
        $query->withTrashed();
      })
      ->when($this->onlyTrashed == true, function ($query) {
        $query->onlyTrashed();
      })
      ->when($this->categoriesFilter, function ($query) {
        $query->whereIn("category_id", $this->categoriesFilter);
      })
      ->when($this->brandsFilter, function ($query) {
        $query->whereIn("brand_id", $this->brandsFilter);
      })
      ->when($this->minPrice, function ($query) {
        $query->where("products.price", ">=", $this->minPrice);
      })
      ->when($this->maxPrice, function ($query) {
        $query->where("products.price", "<=", $this->maxPrice);
      });
    return $products;
  }
  #[Computed()]
  public function products()
  {
    return $this->productsTemplate->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  #[Computed()]
  public function categories()
  {
    return Category::all();
  }
  #[Computed()]
  public function brands()
  {
    return Brand::all();
  }

  public function setPrices()
  {
  }
  public function resetPrices()
  {
    $this->reset("minPrice", "maxPrice");
  }

  #[Computed()]
  public function columns()
  {
    return [
      "name" => "Product",
      "category" => "Category",
      "brand" => "Brand",
      "rating" => "Rating",
      "price" => "Price",
      "sales" => "Sales",
      "revenue" => "Revenue",
      "updated_at" => "Last Update",
    ];
  }
  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
    $this->dispatch("refresh-flowbite");
  }

  #[Computed()]
  public function totalRevenue()
  {
    return Order::all()->sum("total_price");
  }

  public $selectedProduct;
  public function showViewModal($id)
  {
    try {
      $this->selectedProduct = Product::withTrashed()->findOrFail($id);
      $this->dispatch("open-product-view-modal");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Product not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function showEditModal($id)
  {
    try {
      $product = Product::withTrashed()->findOrFail($id);
      $this->selectedProduct = $product;
      $this->editForm->name = $product->name;
      $this->editForm->slug = $product->slug;
      $this->editForm->description = $product->description;
      $this->editForm->price = $product->price;
      $this->editForm->category = $product->category->id;
      $this->editForm->brand = $product->brand->id;
      $this->editForm->product_id = $product->id;
      $this->dispatch("open-product-edit-modal");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Product not found!");
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
  public function update()
  {
    // $this->authorize("edit products");

    try {
      if (!Gate::allows("edit products")) {
        throw new UnauthorizedException("can not edit product");
      }

      $this->editForm->validate();

      if ($this->editForm->image) {
        $imageName = $this->editForm->image->store("product_images", "public");
        if (Storage::disk("public")->exists($this->selectedProduct->image)) {
          Storage::disk("public")->delete($this->selectedProduct->image);
        }
      }

      $this->selectedProduct->updateOrFail([
        "name" => $this->editForm->name,
        "slug" => $this->editForm->slug,
        "description" => $this->editForm->description,
        "price" => $this->editForm->price,
        "category_id" => $this->editForm->category,
        "brand_id" => $this->editForm->brand,
        "image" => $imageName ?? $this->selectedProduct->image,
        "updated_by" => auth()->user()->id,
        "updated_at" => Carbon::now(),
      ]);

      $this->dispatch("close-product-edit-modal");
      $this->dispatch("update_product_success");
    } catch (UnauthorizedException $e) {
      // dd($e->getMessage());
      $this->dispatch("unauthorized-action");
    } catch (Throwable $e) {
      // dd($e->getMessage());
      $this->dispatch("something-went-wrong");
    }
  }

  public function create()
  {
    // $this->authorize("create products");
    try {
      if (!Gate::allows("create products")) {
        throw new UnauthorizedException("can not create product");
      }
      $this->createForm->validate();
      $imageName = $this->createForm->image->store("product_images", "public");
      Product::create([
        "name" => $this->createForm->name,
        "slug" => $this->createForm->slug,
        "description" => $this->createForm->description,
        "price" => $this->createForm->price,
        "category_id" => $this->createForm->category,
        "brand_id" => $this->createForm->brand,
        "image" => $imageName,
        "created_by" => auth()->user()->id,
        "created_at" => Carbon::now(),
      ]);
      $this->dispatch("close-product-create-modal");
      $this->resetCreateFormFields();
      $this->dispatch("create_product_success");
      $this->dispatch("refresh-flowbite");
    } catch (UnauthorizedException $e) {
      // dd($e->getMessage());
      $this->dispatch("unauthorized-action");
    }
  }

  public function resetCreateFormFields()
  {
    $this->createForm->reset();
    $this->createForm->resetErrorBag();
  }

  #[On("delete-product-modal-is-confirmed")]
  public function delete($productId)
  {
    // $this->authorize("delete products");
    try {
      if (!Gate::allows("delete products")) {
        throw new UnauthorizedException("can not delete product");
      }
      $product = Product::findOrFail($productId);

      $existsInDailyDealProducts = DailyDealProduct::where("product_id", $product->id)->exists();
      $existsInWeeklyDealProducts = WeeklyDealProduct::where("product_id", $product->id)->exists();
      $existsInAnyCart = CartItem::where("product_id", $product->id)->exists();

      if ($existsInDailyDealProducts || $existsInWeeklyDealProducts) {
        $this->dispatch(
          "delete_product_error",
          title: "Weekly and/or daily deal record(s) associated with this product found.",
          text: "Delete the associated weekly and/or daily deal record(s) before deleting the product."
        );
      } elseif ($existsInAnyCart) {
        $this->dispatch(
          "delete_product_error",
          title: "Cart item record(s) associated with this product found.",
          text: "You can not delete this product as of now."
        );
      } else {
        $product->delete();
        $product->update([
          "deleted_by" => auth()->user()->id
        ]);
        $this->dispatch("delete_product_success");
      }
    } catch (UnauthorizedException $e) {
      // dd($e->getMessage());
      $this->dispatch("unauthorized-action");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Product not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  #[On("force-delete-product-modal-is-confirmed")]
  public function forceDelete($productId)
  {
    // $this->authorize("force delete products");
    try {
      if (!Gate::allows("force delete products")) {
        throw new UnauthorizedException("you cant delete product permanently");
      }
      Product::withTrashed()->findOrFail($productId)->forceDelete();
      $this->dispatch("force-delete_product_success");
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Product not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function restore($productId)
  {
    try {
      if (!Gate::allows("force delete products")) {
        throw new UnauthorizedException("you cant restore product");
      }
      Product::withTrashed()->findOrFail($productId)->restore();
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Product not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  #[On("product-edit-modal-closed")]
  public function resetFields()
  {
    $this->editForm->resetErrorBag();
    $this->editForm->reset("image");
  }

  #[On("delete_product_success")]
  /* #[On("refresh-table")] */
  public function render()
  {
    return view('livewire.admin.products')->layout("components.admin-layout", ["title" => "Products"])->section("content");
  }
}
