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
  }

  public $step = 1;

  #[On("set-cart-step")]
  public function setStep($step)
  {
    $this->step = $step;
  }

  #[Computed()]
  public function lastViewedProducts()
  {
    $lastViewedProductIDs = Session::get("last_viewed_products", []);

    return Product::whereIn("id", array_keys($lastViewedProductIDs))->get();
  }

  public function render()
  {
    return view('livewire.cart')->layout("components.layout");
  }
}
