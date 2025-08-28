<?php

namespace App\Livewire\Cart;

use App\Traits\CartData;
use Livewire\Attributes\On;
use Livewire\Component;

class CartDrawer extends Component
{
  use CartData;

  #[On("added-to-cart")]
  #[On("refresh-cart")]
  public function render()
  {
    return view('livewire.cart.cart-drawer');
  }
}
