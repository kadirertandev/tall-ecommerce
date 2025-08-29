<?php

namespace App\Livewire\UserProfile;

use App\Models\ProductReview;
use App\Traits\WithRefreshFlowbite;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class UserProfileReviews extends Component
{
  use WithPagination;
  use WithRefreshFlowbite;

  public function boot()
  {
    $this->refreshFlobwite();
  }

  #[Computed()]
  public function reviews()
  {
    return ProductReview::with([
      "product" => fn($q) => $q->with([
        "category" => fn($q) => $q->select(["id", "name", "slug"])->without("brands")
      ])->select(["id", "name", "slug", "image", "category_id"])
    ])
      ->where("user_id", auth()->user()->id)
      ->latest()
      ->simplePaginate(3);
  }

  public function render()
  {
    return view('livewire.user-profile.user-profile-reviews')
      ->layout("components.profile-layout", ["title" => "Reviews"])
      ->section("content");
  }
}
