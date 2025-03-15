<?php

namespace App\Traits;

use App\Models\UserAddress;
use Livewire\Attributes\Computed;

trait CartData
{
  #[Computed()]
  public function cart()
  {
    return auth()->user()->cart;
  }

  #[Computed()]
  public function cartItems()
  {
    return $this->cart->items ?? [];
  }

  #[Computed()]
  public function cartItemsCount()
  {
    return count($this->cartItems);
  }

  #[Computed()]
  public function cartProducts()
  {
    return $this->cart->products() ?? [];
  }

  #[Computed()]
  public function addresses()
  {
    return auth()->user()->addresses;
  }

  #[Computed()]
  public function defaultAddress()
  {
    return auth()->user()->defaultAddress();
  }

  #[Computed()]
  public function nonDefaultAddresses()
  {
    return $this->addresses->where("is_default", 0)->all();
  }

  public $selectedAddress;

  public function updatedSelectedAddress()
  {
    // dd("selected address updated");
    session()->put("selected-address-for-cart", $this->selectedAddress);
  }

  public function showSelectedAddress()
  {
    dd($this->selectedAddress);
  }

  #[Computed()]
  public function finalAddress()
  {
    return UserAddress::find($this->selectedAddress);
  }
}