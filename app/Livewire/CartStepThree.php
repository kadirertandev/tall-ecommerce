<?php

namespace App\Livewire;

use App\Models\UserAddress;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CartStepThree extends BaseCartComponent
{
  public function rendering()
  {
    if ($this->cartItemsCount == 0) {
      $this->dispatch('set-cart-step', step: 1);
    }
  }
  public function mount()
  {
    if (session()->has("selected-address-for-cart")) {
      $this->selectedAddress = session()->get("selected-address-for-cart");
    }
  }

  public function render()
  {
    return view('livewire.cart-step-three');
  }
}
