<?php

namespace App\Livewire;

use App\Models\Product;
use App\Traits\WithTryCatch;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AddToFavoritesButton extends Component
{
  use WithTryCatch;

  public $product_slug;
  public $type;
  public $showLabel;
  public function mount($product_slug, $type, $showLabel = true)
  {
    $this->product_slug = $product_slug;
    $this->type = $type;
    $this->showLabel = $showLabel;
  }

  #[Computed()]
  public function user()
  {
    return auth()->user();
  }

  #[Computed()]
  public function product()
  {
    return Product::where("slug", $this->product_slug)->first();
  }

  public function addToFavorites()
  {
    $this->tryCatch(function () {
      if (!$this->user->favorites()->where("product_id", $this->product->id)->exists()) {
        $this->user->favorites()->attach($this->product, ['created_at' => now()]);
      }

      $this->dispatch("add-to-favorites", product: $this->product, text: __('frontend.favorites.added-to-favorites'));
    });
  }

  public function removeFromFavorites()
  {
    $this->tryCatch(function () {
      if ($this->user->favorites()->where("product_id", $this->product->id)->exists()) {
        $this->user->favorites()->detach($this->product);
      }

      $this->dispatch("remove-from-favorites", product: $this->product, text: __('frontend.favorites.removed-from-favorites'));
    });
  }

  public function render()
  {
    return view('livewire.add-to-favorites-button');
  }
}
