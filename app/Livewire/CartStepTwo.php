<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class CartStepTwo extends BaseCartComponent
{
  /* public $addresses;
  public $selectedAddress;
  public $selectedAddressParent;
  public function mount($addresses, $selectedAddressParent)
  {
    $this->addresses = $addresses;
    $this->selectedAddress = $addresses->where("is_default", 1)->first()->id;
    $this->selectedAddressParent = $selectedAddressParent;
  } */

  public function rendering()
  {
    if ($this->cartItemsCount == 0) {
      $this->dispatch('set-cart-step', step: 1);
    }
  }
  public function mount()
  {
    // dd(session()->has("selected-address-for-cart"));
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
