<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class AddToFavoritesButton extends Component
{
  // public $userID;
  public $product_slug;
  public $isCustom;
  public $buttonClass;
  public $svgClass;
  public $svg = false;
  public $type;
  public function mount($product_slug, $type)
  {
    // dd($isCustom, $buttonClass, $svgClass, $svg);
    // $this->userID = optional(auth()->user())->id;
    $this->product_slug = $product_slug;
    $this->type = $type;
    /*
   $this->isCustom = $isCustom;
   $this->buttonClass = $buttonClass;
   $this->svgClass = $svgClass;
   $this->svg = $svg; */
  }

  #[Computed()]
  public function user()
  {
    // return User::find($this->userID);
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

      if ($this->user->favorites->contains($this->product)) {
        // return $this->dispatch("add-to-favorites-error", product: $this->product, text: __('frontend.favorites.add-to-favorites-error'));
        return $this->dispatch("add-to-favorites", product: $this->product, text: __('frontend.favorites.added-to-favorites'));
      }
      $result = $this->user->favorites()->attach($this->product, ['created_at' => now()]);
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

      if (!$this->user->favorites->contains($this->product)) {
        // return $this->dispatch("remove-from-favorites-error", product: $this->product, text: __('frontend.favorites.remove-from-favorites-error'));
        return $this->dispatch("remove-from-favorites", product: $this->product, text: __('frontend.favorites.removed-from-favorites'));
      }

      $this->user->favorites()->detach($this->product);
      $this->dispatch("remove-from-favorites", product: $this->product, text: __('frontend.favorites.removed-from-favorites'));
    } catch (AuthorizationException $e) {
      $this->dispatch("error-with-message", message: $e->getMessage());
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  #[On("add-to-favorites")]
  #[On("remove-from-favorites")]
  public function render()
  {
    return view('livewire.add-to-favorites-button');
  }
}
