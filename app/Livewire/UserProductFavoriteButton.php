<?php

namespace App\Livewire;

use App\Helpers\IconHelper;
use App\Services\UserProductFavoriteService;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\On;

class UserProductFavoriteButton extends Component
{
  use WithTryCatch;
  use WithSweetAlert;

  public $productId;
  public $type;
  public $showLabel;

  public function mount($productId, $type, $showLabel = true)
  {
    $this->productId = $productId;
    $this->type = $type;
    $this->showLabel = $showLabel;
  }

  #[Computed()]
  public function isInFavorites()
  {
    return auth()->user()?->favorites()->where("product_id", $this->productId)->exists();
  }

  public function addToFavorites(UserProductFavoriteService $userProductFavoriteService)
  {
    $this->tryCatch(function () use ($userProductFavoriteService) {
      $this->authorize("customer");

      $userProductFavoriteService->add($this->productId);

      $this->swalToast([
        "titleText" => __('frontend.favorites.added-to-favorites'),
        "iconHtml" => IconHelper::$svgAddToFavorites,
        "customClass" => [
          "icon" => "border-0!"
        ]
      ]);
    }, [
      AuthorizationException::class => function ($e) {
        if (Gate::allows("view dashboard")) {
          return to_route("admin.dashboard");
        }
        return $this->swalError([
          'titleText' => 'Please log in.',
          'text' => 'You can add product to your favorites after logging in.'
        ]);
        ;
      }
    ]);
  }

  public function removeFromFavorites(UserProductFavoriteService $userProductFavoriteService)
  {
    $this->tryCatch(function () use ($userProductFavoriteService) {
      $userProductFavoriteService->remove($this->productId);

      $this->dispatch("removed-from-favorites");

      $this->swalToast([
        "titleText" => __('frontend.favorites.removed-from-favorites'),
        "iconHtml" => IconHelper::$svgRemoveFromFavorites,
        "customClass" => [
          "icon" => "border-0!"
        ]
      ]);
    });
  }

  #[On("added-to-favorites")]
  public function render()
  {
    return view('livewire.user-product-favorite-button');
  }
}
