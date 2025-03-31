<?php

namespace App\Livewire;

use App\Traits\CartData;
use Livewire\Attributes\On;
use Livewire\Component;

class CartStepOne extends Component
{
  use CartData;

  #[On("refresh-cart")]
  #[On("added-to-cart")]
  public function render()
  {
    return view('livewire.cart-step-one');
  }
}
