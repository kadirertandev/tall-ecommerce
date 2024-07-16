<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class CartStepOne extends BaseCartComponent
{
  /* public $cart;
  public $cartItems;
  public function mount($cart, $cartItems)
  {
    $this->cart = $cart;
    $this->cartItems = $cartItems;
  } */

  public function mount()
  {
    if ($this->cartItemsCount == 0) {
      session()->remove("selected-address-for-cart");
      /* $this->dispatch('set-cart-step', step: 1); */
    }
  }

  #[On("refresh-cart")]
  #[On("added-to-cart")]
  public function render()
  {
    return view('livewire.cart-step-one');
  }
}
