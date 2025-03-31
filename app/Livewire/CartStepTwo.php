<?php

namespace App\Livewire;

use App\Traits\Addresses;
use App\Traits\CartData;
use Livewire\Attributes\On;
use Livewire\Component;

class CartStepTwo extends Component
{
  use CartData;
  use Addresses;

  public function mount()
  {
    if (session()->has("selected-address-id")) {
      $this->selectedAddressId = session()->get("selected-address-id");
    }

    if ($this->cartItemsCount == 0) {
      $this->dispatch('set-cart-step', step: 1);
    }
  }

  public function next()
  {
    if ($this->selectedAddress) {
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
