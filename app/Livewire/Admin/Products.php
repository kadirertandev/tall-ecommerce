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
use App\Traits\WithInteractModal;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class Products extends Component
{
  use WithFileUploads;
  use WithPagination;
  use WithTryCatch;
  use WithRefreshFlowbite;
  use WithSweetAlert;
  use WithInteractModal;

  public ProductCreateForm $createForm;
  public ProductEditForm $editForm;

  public function boot()
  {
    $this->refreshFlobwite();
  }

  public $sortDir = "";
  public $sortBy = "";
  public $keyword = "";
  public $categoriesFilter = [];
  public $brandsFilter = [];
  public $minPrice;
  public $maxPrice;
  public $perPage = 10;
  public $columns = [
    "name" => "Product",
    "category" => "Category",
    "brand" => "Brand",
    "rating" => "Rating",
    "price" => "Price",
    "sales" => "Sales",
    "revenue" => "Revenue",
    "updated_at" => "Last Update",
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

  public function setPrices()
  {
  }
  public function resetPrices()
  {
    $this->reset("minPrice", "maxPrice");
  }

  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
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

  #[Computed()]
  public function products()
  {
    return Product::search($this->keyword)
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
      })
      ->paginate(($this->perPage >= 5) ? $this->perPage : 5);
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

  #[Computed()]
  public function totalRevenue()
  {
    return Order::all()->sum("total_price");
  }

  public $selectedProduct;
  public function showViewModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->selectedProduct = Product::withTrashed()->findOrFail($id);

      $this->showModal("view-product");
    });
  }

  public function showEditModal($id)
  {
    $this->tryCatch(function () use ($id) {
      if (!Gate::allows("edit categories")) {
        throw new UnauthorizedException("can not edit category");
      }

      $product = Product::findOrFail($id);

      $this->selectedProduct = $product;

      $this->editForm->name = $product->name;
      $this->editForm->slug = $product->slug;
      $this->editForm->description = $product->description;
      $this->editForm->price = $product->price;
      $this->editForm->category = $product->category->id;
      $this->editForm->brand = $product->brand->id;
      $this->editForm->product_id = $product->id;

      $this->showModal("edit-product");
    });
  }

  public function create()
  {
    $this->createForm->validate();

    $this->tryCatch(function () {
      if (!Gate::allows("create products")) {
        throw new UnauthorizedException("can not create product");
      }

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

      $this->closeModal("create-product");

      $this->swalToast([
        "titleText" => "Product created successfully!"
      ]);
    });
  }

  public function update()
  {
    $this->editForm->validate();

    $this->tryCatch(function () {
      if (!Gate::allows("edit products")) {
        throw new UnauthorizedException("can not edit product");
      }

      $product = Product::findOrFail($this->selectedProduct->id);

      if ($this->editForm->image) {
        $imageName = $this->editForm->image->store("product_images", "public");
        if (Storage::disk("public")->exists($product->image)) {
          Storage::disk("public")->delete($product->image);
        }
      }

      $product->update([
        "name" => $this->editForm->name,
        "slug" => $this->editForm->slug,
        "description" => $this->editForm->description,
        "price" => $this->editForm->price,
        "category_id" => $this->editForm->category,
        "brand_id" => $this->editForm->brand,
        "image" => $imageName ?? $product->image,
        "updated_by" => auth()->user()->id,
        "updated_at" => Carbon::now(),
      ]);

      $this->selectedProduct = $product;

      $this->closeModal("edit-product");

      $this->swalToast([
        "titleText" => "Product updated successfully!"
      ]);
    });
  }

  public function askDeleteProduct($productId, $permanently = false)
  {
    $this->swalQuestion([
      "titleText" => "Are you sure you want to delete this product " . ($permanently ? "permanently?" : "?"),
      "confirmButtonText" => 'Yes',
      "denyButtonText" => "No",
      "onConfirm" => (!$permanently ? "" : "force-") . "delete-product-confirmed",
      "onConfirmParameters" => [
        "productId" => $productId
      ],
      "customClass" => [
        "title" => "text-nowrap!",
        "popup" => "min-w-max!"
      ]
    ]);
  }

  #[On("delete-product-confirmed")]
  public function delete($productId)
  {
    $this->tryCatch(function () use ($productId) {
      if (!Gate::allows("delete products")) {
        throw new UnauthorizedException("can not delete product");
      }

      $product = Product::findOrFail($productId);

      $existsInDailyDealProducts = DailyDealProduct::where("product_id", $productId)->exists();
      $existsInWeeklyDealProducts = WeeklyDealProduct::where("product_id", $productId)->exists();
      $existsInAnyCart = CartItem::where("product_id", $productId)->exists();

      if ($existsInDailyDealProducts || $existsInWeeklyDealProducts) {
        $this->swalTemplateAssociatedExistsError([
          "titleText" => "Weekly and/or daily deal record(s) associated with this product found.",
          "text" => "Delete the associated weekly and/or daily deal record(s) before deleting the product."
        ]);
      } elseif ($existsInAnyCart) {
        $this->swalTemplateAssociatedExistsError([
          "titleText" => "Cart item record(s) associated with this product found.",
          "text" => "You can not delete this product as of now.",
        ]);
      } else {
        $product->delete();
        $product->update([
          "deleted_by" => auth()->user()->id
        ]);

        $this->swalToast([
          "titleText" => "Product deleted successfully!"
        ]);
      }
    });
  }

  #[On("force-delete-product-confirmed")]
  public function forceDelete($productId)
  {
    $this->tryCatch(function () use ($productId) {
      if (!Gate::allows("force delete products")) {
        throw new UnauthorizedException("you cant delete product permanently");
      }

      Product::withTrashed()->findOrFail($productId)->forceDelete();

      $this->swalToast([
        "titleText" => "Product deleted permanently successfully!"
      ]);
    });
  }

  public function restore($productId)
  {
    $this->tryCatch(function () use ($productId) {
      if (!Gate::allows("force delete products")) {
        throw new UnauthorizedException("you cant restore product");
      }

      Product::withTrashed()->findOrFail($productId)->restore();

      $this->swalToast([
        "titleText" => "Product restored successfully!"
      ]);
    });
  }

  public function render()
  {
    return view('livewire.admin.products')
      ->layout("components.admin-layout", ["title" => "Products"])
      ->section("content");
  }
}
