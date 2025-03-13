<?php

namespace App\Livewire;

use App\Traits\WithTryCatch;
use Livewire\Component;

class AddToFavoritesButton extends Component
{
  use WithTryCatch;

  public $productId;
  public $type;
  public $showLabel;
  public function mount($productId, $type, $showLabel = true)
  {
    $this->productId = $productId;
    $this->type = $type;
    $this->showLabel = $showLabel;
  }

  public function addToFavorites()
  {
    $this->tryCatch(function () {
      if (!auth()->user()->favorites()->where("product_id", $this->productId)->exists()) {
        auth()->user()->favorites()->attach($this->productId, ['created_at' => now()]);
      }

      $this->dispatch("add-to-favorites", text: __('frontend.favorites.added-to-favorites'));
    });
  }

  public function removeFromFavorites()
  {
    $this->tryCatch(function () {
      if (auth()->user()->favorites()->where("product_id", $this->productId)->exists()) {
        auth()->user()->favorites()->detach($this->productId);
      }

      $this->dispatch("remove-from-favorites", text: __('frontend.favorites.removed-from-favorites'));
    });
  }

  public function render()
  {
    return view('livewire.add-to-favorites-button');
  }
}
