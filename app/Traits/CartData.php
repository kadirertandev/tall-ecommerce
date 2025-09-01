<?php

namespace App\Traits;

use App\Models\UserAddress;
use Livewire\Attributes\Computed;

trait CartData
{
  public $selectedAddressId;

  public function updatedSelectedAddressId()
  {
    session()->put("selected-address-id", $this->selectedAddressId);
  }

  #[Computed()]
  public function cart()
  {
    return auth()->user()->cart;
  }

  #[Computed()]
  public function cartItems()
  {
    return $this->cart?->items()
      ->with([
        "product" => fn($q) => $q->with([
          "category" => fn($q) => $q->without("brands")
        ])
      ])
      ->get() ?? [];
  }

  #[Computed()]
  public function cartSubtotal()
  {
    return $this->cart?->subtotal();
  }

  #[Computed()]
  public function cartItemsCount()
  {
    return count($this->cartItems);
  }

  #[Computed()]
  public function selectedAddress()
  {
    return UserAddress::find($this->selectedAddressId);
  }
}