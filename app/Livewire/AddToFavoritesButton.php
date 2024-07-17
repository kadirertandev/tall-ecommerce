<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Throwable;

class AddToFavoritesButton extends Component
{
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
    try {
      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }

      $this->user->favorites()->attach($this->product, ['created_at' => now()]);
      $this->dispatch("add-to-favorites", product: $this->product, text: __('frontend.favorites.added-to-favorites'));
    } catch (AuthorizationException $e) {
      $this->dispatch("error-with-message", message: $e->getMessage());
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function removeFromFavorites()
  {
    try {
      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }

      $this->user->favorites()->detach($this->product);
      $this->dispatch("remove-from-favorites", product: $this->product, text: __('frontend.favorites.removed-from-favorites'));
    } catch (AuthorizationException $e) {
      $this->dispatch("error-with-message", message: $e->getMessage());
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function render()
  {
    return view('livewire.add-to-favorites-button');
  }
}
