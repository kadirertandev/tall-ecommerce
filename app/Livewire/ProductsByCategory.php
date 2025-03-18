<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Traits\SortOptions;
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

  public function mount($slug)
  {
    $this->slug = $slug;
    $this->orderFrontend = Lang::get("frontend.filters.newest");
    $this->breadcrumbs = [
      [
        "name" => !Str::startsWith(__('categories.' . $this->category->slug . '.name'), 'categories.')
          ? __('categories.' . $this->category->slug . '.name')
          : __('categories.' . __('categories.dictionary.' . $this->category->slug) . '.name'),
        "url" => route("category-slug", ["slug" => $this->slug])
      ]
    ];
  }

  public function setPrices()
  {
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
    return Product::where("category_id", $this->category->id)
      ->when(count($this->selectedBrands) > 0, function ($query) {
        $this->resetPage();
        return $query->whereIn("brand_id", $this->selectedBrands);
      })
      ->when($this->minPrice, function ($query) {
        $this->resetPage();
        return $query->where("price", ">=", $this->minPrice);
      })
      ->when($this->maxPrice, function ($query) {
        $this->resetPage();
        return $query->where("price", "<=", $this->maxPrice);
      })
      ->when($this->orderBy !== "most_liked", function ($query) {
        $this->resetPage();
        return $query->orderBy($this->orderBy, $this->sortDir);
      })
      ->when($this->orderBy === "most_liked", function ($query) {
        $this->resetPage();
        return $query->leftJoin("product_reviews", "product_reviews.product_id", "=", "products.id")
          ->select(["products.*", DB::raw("sum(case when product_reviews.status != 'approved' then 0 else product_reviews.rating end) as rating")])
          ->groupBy("products.id")
          ->orderBy("rating", $this->sortDir);
      })
      ->paginate($this->perPage);
  }

  public function loadMore()
  {
    $this->perPage += 6;
  }

  #[Computed()]
  public function canLoadMore()
  {
    return $this->products()->currentPage() != $this->products()->lastPage();
  }

  public function render()
  {
    return view('livewire.products-by-category')->layout("components.layout", ["title" => $this->category->name]);
  }
}
