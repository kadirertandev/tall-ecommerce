<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\Admin\ProductCreateForm;
use App\Livewire\Forms\Admin\ProductEditForm;
use App\Models\Brand;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\DailyDealProduct;
use App\Models\Product;
use App\Models\WeeklyDealProduct;
use App\Traits\WithInteractModal;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithRemoveFormImage;
use App\Traits\WithSoftDeleteFilter;
use App\Traits\WithSweetAlert;
use App\Traits\WithTableSortAndFilter;
use App\Traits\WithTryCatch;
use App\Traits\WithUpdateFormSlug;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
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
  use WithTableSortAndFilter;
  use WithSoftDeleteFilter;
  use WithUpdateFormSlug;
  use WithRemoveFormImage;

  public ProductCreateForm $createForm;
  public ProductEditForm $editForm;

  public function boot()
  {
    $this->refreshFlobwite();
  }

  public $categoriesFilter = [];
  public $brandsFilter = [];
  public $minPrice;
  public $maxPrice;
  public $columns = [
    "name" => "Product",
    "category_name" => "Category",
    "brand_name" => "Brand",
    "review_rating_average" => "Rating",
    "price" => "Price",
    "total_sales" => "Sales",
    "total_revenue" => "Revenue",
    "updated_at" => "Last Update",
  ];

  public function setPrices($minPrice, $maxPrice)
  {
    $this->minPrice = $minPrice;
    $this->maxPrice = $maxPrice;
  }
  public function resetPrices()
  {
    $this->reset("minPrice", "maxPrice");
  }

  #[Computed()]
  public function products()
  {
    return Product::with([
      "category" => fn($q) => $q->select(["id", "name", "slug"])->without("brands"),
      "brand" => fn($q) => $q->select(["id", "name", "slug"])
    ])
      ->search($this->keyword)
      ->withoutColumns(["slug", "title", "deleted_by", "created_by", "created_at", "deleted_by", "updated_by"])
      ->withSubQueryFields()
      ->filterByTrashed($this->withTrashed, $this->onlyTrashed)
      ->filterByCategory($this->categoriesFilter)
      ->filterByBrand($this->brandsFilter)
      ->filterByPrice($this->minPrice, $this->maxPrice)
      ->sortByColumn($this->sortBy, $this->sortDir)
      ->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  #[Computed()]
  public function categories()
  {
    return Cache::remember("admin-products-categories", 60 * 5, function () {
      return Category::select(["id", "name"])->get();
    });
  }

  #[Computed()]
  public function brands()
  {
    return Cache::remember("admin-products-brands", 60 * 5, function () {
      return Brand::select(["id", "name"])->get();
    });
  }

  public $selectedProduct;

  public function showViewModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->selectedProduct = Product::with([
        "category" => fn($q) => $q->select(["id", "name", "slug"])->without("brands"),
        "brand" => fn($q) => $q->select(["id", "name", "slug"])
      ])
        ->withTrashed()->withSubQueryFields()->findOrFail($id);

      $this->showModal("view-product");
    });
  }

  public function showEditModal($id)
  {
    $this->tryCatch(function () use ($id) {
      if (!Gate::allows("edit products")) {
        throw new UnauthorizedException("can not edit product");
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

  public function afterModalClosed($modalName)
  {
    if ($modalName == "view-product") {
      $this->reset("selectedProduct");
    }
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

      $product = Product::withTrashed()->findOrFail($productId);
      $product->restore();
      $product->update([
        "deleted_by" => null
      ]);

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
