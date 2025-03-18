<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use App\Traits\SortOptions;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Lang;

class ProductsByBrand extends Component
{
  use WithPagination;
  use SortOptions;

  public $slug = "";

  #[Url()]
  public $selectedCategories = [];

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
        "name" => $this->brand()->name,
        "url" => route("brand-slug", ["slug" => $this->slug])
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
  public function brand()
  {
    return Brand::with("categories")->where("slug", $this->slug)->firstOrFail();
  }

  #[Computed()]
  public function products()
  {
    return Product::where("brand_id", $this->brand->id)
      ->when(count($this->selectedCategories) > 0, function ($query) {
        $this->resetPage();
        return $query->whereIn("category_id", $this->selectedCategories);
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
    return view('livewire.products-by-brand')->layout("components.layout", ["title" => $this->brand->name]);
  }
}
