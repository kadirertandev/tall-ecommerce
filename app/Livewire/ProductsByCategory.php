<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Traits\SortOptions;
use App\Traits\WithRefreshFlowbite;
use Illuminate\Support\Facades\Cache;
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
    $this->validateOrderByInputs();

    $this->slug = $slug;
    $this->orderFrontend = Lang::get("frontend.filters.newest");
    $this->breadcrumbs = [
      [
        "name" => __("categories.{$this->slug}.name"),
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
    return Cache::remember("category_with_slug_{$this->slug}", 60 * 5, function () {
      return Category::with("brands")->where("slug", $this->slug)
        ->firstOrFail();
    });
  }

  public $allowedColumns = ["price", "most_liked", "created_at"];

  #[Computed()]
  public function products()
  {
    $products = Product::with([
      "category" => fn($q) => $q->select(["id", "name", "slug"])->without("brands"),
      "brand" => fn($q) => $q->select(["id", "name", "slug"])
    ])
      ->where("category_id", $this->category->id)
      ->withoutColumns(["created_by", "updated_by", "updated_at", "deleted_at", "deleted_by"])
      ->withReviewRatingAverageAndReviewCount()
      ->filterByBrand($this->selectedBrands)
      ->filterByprice($this->minPrice, $this->maxPrice)
      ->when($this->orderByColumn === "most_liked", fn($query) => $query->sortByColumn("review_rating_average", $this->orderByDirection))
      ->when($this->orderByColumn !== "most_liked", fn($query) => $query->sortByColumn($this->orderByColumn, $this->orderByDirection));

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
