<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UserProfileFavorites extends Component
{
  use WithPagination;
  public $userID;
  #[Url()]
  public $search;
  #[Url()]
  public $categoriesFilter = [];
  #[Url()]
  public $searchCategory;
  #[Url()]
  public $orderBy = "created_at";
  #[Url()]
  public $sortDir = "desc";
  public $perPage = 6;
  public $cols = 4;
  public $orderFrontend = "";

  public function mount()
  {
    $this->userID = auth()->user()->id;
    $this->orderFrontend = Lang::get("frontend.filters.newest");
  }

  #[Computed()]
  public function categories()
  {
    return Category::where("name", "like", "%" . $this->searchCategory . "%")
      ->orWhere("slug", "like", "%" . $this->searchCategory . "%")
      ->pluck("name", "id");
    // ->get();
  }

  #[Computed()]
  public function user()
  {
    return User::find($this->userID);
  }

  #[Computed()]
  public function favorites()
  {
    return Product::search($this->search)
      ->leftJoin("user_product_favorites", "user_product_favorites.product_id", "=", "products.id")
      ->where("user_product_favorites.user_id", auth()->user()->id)
      ->when($this->orderBy === "created_at", function ($query) {
        return $query->orderBy("user_product_favorites.created_at", "desc");
      })
      ->when($this->orderBy !== "created_at", function ($query) {
        return $query->orderBy($this->orderBy, $this->sortDir);
      })
      ->when($this->categoriesFilter, function ($query) {
        return $query->whereIn("products.category_id", $this->categoriesFilter);
      })
      ->paginate($this->perPage);
  }

  #[Computed()]
  public function favoritesCount()
  {
    return $this->favorites->count();
  }

  public function removeFromFavorites($id)
  {
    $product = Product::find($id);

    $this->user->favorites()->detach($product);
    $this->dispatch("remove-from-favorites", product: $product, text: __('frontend.favorites.removed-from-favorites'));
  }

  public function lowestPrice()
  {
    $this->orderBy = "price";
    $this->sortDir = "asc";
    $this->orderFrontend = Lang::get("frontend.filters.lowest-price");
    // $this->resetPage();
  }
  public function highestPrice()
  {
    $this->orderBy = "price";
    $this->sortDir = "desc";
    $this->orderFrontend = Lang::get("frontend.filters.highest-price");
    // $this->resetPage();
  }
  public function lastAdded()
  {
    $this->orderBy = "created_at";
    $this->sortDir = "desc";
    $this->orderFrontend = Lang::get("frontend.filters.newest");
    // $this->resetPage();
  }

  #[On("remove-from-favorites")]
  public function render()
  {
    return view('livewire.user-profile-favorites');
  }
}
