<?php

namespace App\Livewire;

use App\Traits\CartActions;
use App\Traits\CartData;
use Livewire\Attributes\On;
use Livewire\Component;

class CartOnNav extends Component
{
  use CartActions;
  use CartData;

  #[On("added-to-cart")]
  public function render()
  {
    return view('livewire.cart-on-nav');
  }
}
