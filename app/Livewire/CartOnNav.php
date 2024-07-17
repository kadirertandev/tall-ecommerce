<?php

namespace App\Livewire;

use Livewire\Attributes\On;

class CartOnNav extends BaseCartComponent
{
  #[On("added-to-cart")]
  public function render()
  {
    return view('livewire.cart-on-nav');
  }
}
