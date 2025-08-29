<?php

namespace App\Livewire\UserProfile;

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

  public function mount()
  {
    $this->validateOrderByInputs();

    $this->orderFrontend = Lang::get("frontend.filters.newest");
  }

  #[Computed()]
  public function categories()
  {
    return Category::where("name", "like", "%" . $this->searchCategory . "%")
      ->orWhere("slug", "like", "%" . $this->searchCategory . "%")
      ->pluck("name", "id");
  }

  public $allowedColumns = ["price", "most_liked", "created_at"];

  #[Computed()]
  public function favorites()
  {
    return Product::with([
      "category" => fn($q) => $q->select(["id", "name", "slug"])->without("brands"),
      "brand" => fn($q) => $q->select(["id", "name", "slug"])
    ])
      ->search($this->search)
      ->join("user_product_favorites", "user_product_favorites.product_id", "=", "products.id")
      ->where("user_product_favorites.user_id", auth()->user()->id)
      ->when($this->orderByColumn === "created_at", function ($query) {
        return $query->orderBy("user_product_favorites.created_at", "desc");
      }, function ($query) {
        return $query->sortByColumn($this->orderByColumn, $this->orderByDirection);
      })
      ->select(["products.*"])
      ->filterByCategory($this->categoriesFilter)
      ->paginate($this->perPage);
  }

  #[On("removed-from-favorites")]
  public function render()
  {
    return view('livewire.user-profile.user-profile-favorites')
      ->layout("components.profile-layout", ["title" => "Favorites"])
      ->section("content");
  }
}
