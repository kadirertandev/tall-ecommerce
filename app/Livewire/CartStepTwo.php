<?php

namespace App\Livewire;

use App\Traits\Addresses;
use Livewire\Attributes\On;

class CartStepTwo extends BaseCartComponent
{
  use Addresses;
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
    } else {
      $this->selectedAddress = $this->defaultAddress?->id;
    }
  }

  public function next()
  {
    session()->put("selected-address-for-cart", $this->selectedAddress);
    if ($this->finalAddress) {
      $this->dispatch('set-cart-step', step: 3);
    } else {
      $this->addError("address-required", "Address Required");
    }
  }

  #[On("added-to-cart")]
  public function render()
  {
    return view('livewire.cart-step-two');
  }
}
