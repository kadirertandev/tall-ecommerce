<?php

namespace App\Livewire;

use App\Traits\CartActions;
use App\Traits\CartData;
use Livewire\Attributes\On;
use Livewire\Component;

class CartOnNav extends Component
{
  use CartData;
  use CartActions {
    CartActions::removeFromCart as traitRemoveFromCart;
  }

  #[On("remove-product-from-cart-confirmed")]
  #[On("remove-product-from-cart-denied")]
  public function removeFromCart($cartItemId, $addToFavorites)
  {
    $this->traitRemoveFromCart($cartItemId, $addToFavorites);
  }

  #[On("added-to-cart")]
  public function render()
  {
    return view('livewire.cart-on-nav');
  }
}
