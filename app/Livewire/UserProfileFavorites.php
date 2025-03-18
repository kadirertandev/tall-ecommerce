<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Traits\SortOptions;
use Illuminate\Support\Facades\Lang;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UserProfileFavorites extends Component
{
  use WithPagination;
  use SortOptions;

  #[Url()]
  public $search;

  #[Url()]
  public $categoriesFilter = [];

  #[Url()]
  public $searchCategory;

  #[Url()]
  public $perPage = 6;

  public $cols = 4;

  public function mount()
  {
    $this->orderFrontend = Lang::get("frontend.filters.newest");
  }

  #[Computed()]
  public function categories()
  {
    return Category::where("name", "like", "%" . $this->searchCategory . "%")
      ->orWhere("slug", "like", "%" . $this->searchCategory . "%")
      ->pluck("name", "id");
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
      ->select(["products.*"])
      ->paginate($this->perPage);
  }

  #[On("removed-from-favorites")]
  public function render()
  {
    return view('livewire.user-profile-favorites');
  }
}
