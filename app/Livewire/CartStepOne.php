<?php

namespace App\Livewire;

use Livewire\Attributes\On;

class CartStepOne extends BaseCartComponent
{
  public function mount()
  {
    if ($this->cartItemsCount == 0) {
      session()->remove("selected-address-for-cart");
    }
  }

  #[On("refresh-cart")]
  #[On("added-to-cart")]
  public function render()
  {
    return view('livewire.cart-step-one');
  }
}
