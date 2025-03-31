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
  public function setStep($step)
  {
    $this->step = $step;
    session(["cart_step" => $this->step]);
  }

  #[Computed()]
  public function lastViewedProducts()
  {
    $lastViewedProductIDs = Session::get("last_viewed_products", []);

    return Product::whereIn("id", array_keys($lastViewedProductIDs))
      ->with(["category" => fn($q) => $q->with("brands"), "brand"])->get();
  }

  #[On("remove-product-from-cart-confirmed")]
  #[On("remove-product-from-cart-denied")]
  public function removeFromCart($cartItemId, $addToFavorites)
  {
    $this->traitRemoveFromCart($cartItemId, $addToFavorites);
  }

  public function render()
  {
    return view('livewire.cart')->layout("components.layout");
  }
}
