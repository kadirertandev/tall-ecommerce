<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsByCategory extends Component
{
  use WithPagination;

  #[Url(keep: false)]
  public $page = 1;
  public function udpatedPage()
  {
    // dd("page updated. do something");
  }
  public $slug;
  #[Url()]
  public $selectedBrands = [];
  public function updatedSelectedBrands()
  {
    $this->js("console.log(" . json_encode($this->selectedBrands) . ")");
  }
  #[Url()]
  public $minPrice;
  #[Url()]
  public $maxPrice;
  #[Url()]
  public $orderBy = "created_at";
  #[Url()]
  public $orderDir = "desc";
  public $orderFrontend;

  public $breadcrumbs = [];

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

  #[On("orderByLowestPrice")]
  public function orderByLowestPrice()
  {
    // dd("lowest price");
    $this->orderBy = "price";
    $this->orderDir = "asc";
    $this->orderFrontend = Lang::get("frontend.filters.lowest-price");
  }
  #[On("orderByHighestPrice")]
  public function orderByHighestPrice()
  {
    $this->orderBy = "price";
    $this->orderDir = "desc";
    $this->orderFrontend = Lang::get("frontend.filters.highest-price");
  }
  #[On("orderByMostLiked")]
  public function orderByMostLiked()
  {
    $this->orderBy = "most_liked";
    $this->orderDir = "desc";
    $this->orderFrontend = "En çok beğenilenler";
  }
  #[On("orderByNewest")]
  public function orderByNewest()
  {
    $this->orderBy = "created_at";
    $this->orderDir = "desc";
    $this->orderFrontend = Lang::get("frontend.filters.newest");
  }
  #[On("orderByMostReviewed")]
  public function orderByMostReviewed()
  {
    $this->orderBy = "review";
    $this->orderDir = "desc";
    $this->orderFrontend = "En çok değerlendirilenler";
  }

  #[Computed()]
  public function category()
  {
    $category = Category::where("slug", $this->slug)
      ->orWhere("slug", __("categories.dictionary." . $this->slug))
      ->first();

    if (!$category) {
      abort(404);
    }
    return $category;
  }

  #[Computed()]
  public function categoryBrands()
  {
    return $this->category->brands;
  }

  #[Computed()]
  public function productsTemplate()
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
      ->when($this->orderBy === "price", function ($query) {
        $this->resetPage();
        return $query->orderBy($this->orderBy, $this->orderDir);
      });
  }

  public $perPage = 6;
  #[Computed()]
  public function products()
  {
    return $this->productsTemplate()->paginate($this->perPage, ["*"], "page", $this->page);
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
