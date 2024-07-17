<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Lang;

class ProductsByBrand extends Component
{
  use WithPagination;
  public $page = 1;
  public function updatedPage()
  {
    // dd($this->page);
  }
  public $slug = "";
  public $breadcrumbs = [];
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
  #[Url()]
  public $selectedCategories = [];
  public function updatedSelectedCategories()
  {
    $this->js("console.log(" . json_encode($this->selectedCategories) . ")");
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
    $this->orderFrontend = Lang::get("frontend.filters.most-liked");
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
    $this->orderFrontend = Lang::get("frontend.filters.most-reviewed");
  }


  #[Computed()]
  public function brand()
  {
    $brand = Brand::where("slug", $this->slug)->first();

    if (!$brand) {
      abort(404);
    }
    return $brand;
  }
  #[Computed()]
  public function brandCategories()
  {
    return $this->brand->categories;
  }

  #[Computed()]
  public function productsTemplate()
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
      ->when($this->orderBy === "price", function ($query) {
        $this->resetPage();
        return $query->orderBy($this->orderBy, $this->orderDir);
      });
  }

  public $perPage = 6;
  #[Computed()]
  public function products()
  {
    return $this->productsTemplate()->paginate($this->perPage);
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
