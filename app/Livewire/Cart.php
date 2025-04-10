<?php

namespace App\Livewire;

use App\Models\Product;
use App\Traits\CartActions;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Cart extends Component
{
  use CartActions {
    CartActions::increaseQuantity as traitIncreaseQuantity;
    CartActions::decreaseQuantity as traitDecreaseQuantity;
    CartActions::askRemoveFromCart as traitAskRemoveFromCart;
    CartActions::removeFromCart as traitRemoveFromCart;
  }

  public $step = 1;

  public function boot()
  {
    $this->step = session()->get("cart_step", $this->step);
  }

  #[On("set-cart-step")]
  public function setStep($step, $source = "continue")
  {
    if ($source === "step-button" && $this->step <= $step)
      return;

    $this->step = $step;

    session(["cart_step" => $step]);
  }

  #[On("added-to-cart")]
  public function resetStep()
  {
    $this->setStep(1);
  }

  #[On("remove-product-from-cart-confirmed")]
  #[On("remove-product-from-cart-denied")]
  public function removeFromCart($cartItemId, $addToFavorites)
  {
    $this->traitRemoveFromCart($cartItemId, $addToFavorites);
  }

  #[Computed()]
  public function lastViewedProducts()
  {
    $lastViewedProductIDs = Session::get("last_viewed_products", []);

    return Product::whereIn("id", array_keys($lastViewedProductIDs))
      ->with([
        "category" => fn($q) => $q->select(["id", "name", "slug"])->without("brands"),
        "brand" => fn($q) => $q->select(["id", "name", "slug"])
      ])
      ->withReviewRatingAverageAndReviewCount()
      ->get();
  }

  public function render()
  {
    return view('livewire.cart')->layout("components.layout");
  }
}
