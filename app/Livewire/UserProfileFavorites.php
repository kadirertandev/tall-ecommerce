<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\UserProductFavorite;
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
    return $this->user->favorites()
      ->orderBy($this->orderBy, $this->sortDir)
      ->search($this->search)
      ->when($this->categoriesFilter, function ($query) {
        return $query->whereIn("category_id", $this->categoriesFilter);
      })
      ->paginate($this->perPage);
    /* if ($this->orderBy === "created_at") {
      $query = UserProductFavorite::where('user_id', $this->user->id)
        ->with("product")
        ->orderBy('created_at', 'desc');

      // Apply search filter
      if (!empty($this->search)) {
        $query->whereHas('product', function ($q) {
          $q->where('name', 'like', '%' . $this->search . '%');
        });
      }

      // Apply category filter
      if (!empty($this->categoriesFilter)) {
        $query->whereHas('product', function ($q) {
          $q->whereIn('category_id', $this->categoriesFilter);
        });
      }

      return $query->paginate($this->perPage);
    } elseif ($this->orderBy === "price") {
      return $this->user->favorites()
        ->orderBy($this->orderBy, $this->sortDir)
        ->search($this->search)
        ->when($this->categoriesFilter, function ($query) {
          return $query->whereIn("category_id", $this->categoriesFilter);
        })
        ->paginate($this->perPage);
    } */
  }

  #[Computed()]
  public function favoritesCount()
  {
    return $this->user->favorites->count();
  }

  public function removeFromFavorites($id)
  {
    $product = Product::find($id);
    if (!$this->user->favorites->contains($product)) {
      // return $this->dispatch("remove-from-favorites-error", product: $product, text: __('frontend.favorites.remove-from-favorites-error'));
      return $this->dispatch("remove-from-favorites", product: $product, text: __('frontend.favorites.removed-from-favorites'));
    }
    $this->user->favorites()->detach($product);
    $this->dispatch("remove-from-favorites", product: $product, text: __('frontend.favorites.removed-from-favorites'));
  }

  // #[On("lowest-price")]
  public function lowestPrice()
  {
    $this->orderBy = "price";
    $this->sortDir = "asc";
    $this->orderFrontend = Lang::get("frontend.filters.lowest-price");
    // $this->resetPage();
  }
  // #[On("highest-price")]
  public function highestPrice()
  {
    $this->orderBy = "price";
    $this->sortDir = "desc";
    $this->orderFrontend = Lang::get("frontend.filters.highest-price");
    // $this->resetPage();
  }
  // #[On("last-added")]
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
