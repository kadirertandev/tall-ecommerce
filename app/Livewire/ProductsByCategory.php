<?php

namespace App\Livewire;

use App\Enums\ReviewStatusType;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Traits\SortOptions;
use App\Traits\WithRefreshFlowbite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsByCategory extends Component
{
  use WithPagination;
  use WithRefreshFlowbite;
  use SortOptions;

  public $slug;
  #[Url()]
  public $selectedBrands = [];

  #[Url()]
  public $minPrice;

  #[Url()]
  public $maxPrice;

  public $breadcrumbs = [];

  public $perPage = 6;

  public function boot()
  {
    $this->refreshFlobwite();
  }

  public function mount($slug)
  {
    $this->slug = $slug;
    $this->orderFrontend = Lang::get("frontend.filters.newest");
    $this->breadcrumbs = [
      [
        "name" => !Str::startsWith(__('categories.' . $this->slug . '.name'), 'categories.')
          ? __('categories.' . $this->slug . '.name')
          : __('categories.' . __('categories.dictionary.' . $this->slug) . '.name'),
        "url" => route("category-slug", ["slug" => $this->slug])
      ]
    ];
  }

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
  public function category()
  {
    return Category::with("brands")->where("slug", $this->slug)
      ->orWhere("slug", __("categories.dictionary." . $this->slug))
      ->firstOrFail();
  }

  #[Computed()]
  public function products()
  {
    $products = Product::where("category_id", $this->category->id)
      ->withoutColumns(["created_by", "updated_by", "updated_at", "deleted_at", "deleted_by"])
      ->addSelect([
        "rating" => ProductReview::select(DB::raw("avg(rating)"))
          ->whereColumn("product_id", "products.id")
          ->where("status", ReviewStatusType::from("approved")),

        "review_count" => ProductReview::select(DB::raw("count(id)"))
          ->whereColumn("product_id", "products.id")
          ->where("status", ReviewStatusType::from("approved"))
      ])
      ->filterByBrand($this->selectedBrands)
      ->filterByprice($this->minPrice, $this->maxPrice)
      ->when($this->orderBy === "most_liked", fn($query) => $query->sortByColumn("rating", $this->sortDir))
      ->when($this->orderBy !== "most_liked", fn($query) => $query->sortByColumn($this->orderBy, $this->sortDir));

    $total = $products->count();

    if ($total <= $this->perPage) {
      $this->setPage(1);
    }

    $paginatedProducts = $products->simplePaginate($this->perPage);

    return [
      "products" => $paginatedProducts,
      "total" => $total
    ];
  }

  public function render()
  {
    return view('livewire.products-by-category')
      ->layout("components.layout", ["title" => $this->category->name]);
  }
}
