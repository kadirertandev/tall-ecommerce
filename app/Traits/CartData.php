<?php

namespace App\Traits;

use App\Models\UserAddress;
use Livewire\Attributes\Computed;

trait CartData
{
  protected $svgAddToCart = '<svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16" viewBox="0 0 24 24">
	<path fill="currentColor" fill-rule="evenodd" d="M3.04 2.292a.75.75 0 0 0-.497 1.416l.261.091c.668.235 1.107.39 1.43.549c.303.149.436.27.524.398c.09.132.16.314.2.677c.04.38.042.875.042 1.615V9.64c0 2.942.063 3.912.93 4.826c.866.914 2.26.914 5.05.914h5.302c1.561 0 2.342 0 2.893-.45c.552-.45.71-1.214 1.025-2.742l.5-2.425c.347-1.74.52-2.609.076-3.186S18.816 6 17.131 6H6.492a9 9 0 0 0-.043-.738c-.054-.497-.17-.95-.452-1.362c-.284-.416-.662-.682-1.103-.899c-.412-.202-.936-.386-1.552-.603zm12.477 6.165c.3.286.312.76.026 1.06l-2.857 3a.75.75 0 0 1-1.086 0l-1.143-1.2a.75.75 0 1 1 1.086-1.034l.6.63l2.314-2.43a.75.75 0 0 1 1.06-.026" clip-rule="evenodd" />
	<path fill="currentColor" d="M7.5 18a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m9 0a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3" />
</svg>';

  protected $svgRemoveFromCart = '<svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16" viewBox="0 0 24 24">
	<path fill="currentColor" fill-rule="evenodd" d="M3.04 2.292a.75.75 0 0 0-.497 1.416l.261.091c.668.235 1.107.39 1.43.549c.303.149.436.27.524.398c.09.132.16.314.2.677c.04.38.042.875.042 1.615V9.64c0 2.942.063 3.912.93 4.826c.866.914 2.26.914 5.05.914h5.302c1.561 0 2.342 0 2.893-.45c.552-.45.71-1.214 1.025-2.742l.5-2.425c.347-1.74.52-2.609.076-3.186S18.816 6 17.131 6H6.492a9 9 0 0 0-.043-.738c-.054-.497-.17-.95-.452-1.362c-.284-.416-.662-.682-1.103-.899c-.412-.202-.936-.386-1.552-.603zm7.93 6.678a.75.75 0 0 1 1.06 0l.97.97l.97-.97a.75.75 0 1 1 1.06 1.06l-.97.97l.97.97a.75.75 0 1 1-1.06 1.06l-.97-.97l-.97.97a.75.75 0 1 1-1.06-1.06l.97-.97l-.97-.97a.75.75 0 0 1 0-1.06" clip-rule="evenodd" />
	<path fill="currentColor" d="M7.5 18a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m9 0a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3" />
</svg>';

  protected $svgRemoveFromCartAddToFavorites = '<svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16" viewBox="0 0 24 24">
	<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
		<path d="M4 19a2 2 0 1 0 4 0a2 2 0 0 0-4 0" />
		<path d="M10 17H6V3H4" />
		<path d="m6 5l14 1l-.717 5.016M11.5 13H6m12 9l3.35-3.284a2.143 2.143 0 0 0 .005-3.071a2.24 2.24 0 0 0-3.129-.006l-.224.22l-.223-.22a2.24 2.24 0 0 0-3.128-.006a2.143 2.143 0 0 0-.006 3.071z" />
	</g>
</svg>';


  #[Computed()]
  public function cart()
  {
    return auth()->user()->cart;
  }

  #[Computed()]
  public function cartItems()
  {
    return $this->cart?->items()->with("product")->get() ?? [];
  }

  #[Computed()]
  public function cartItemsCount()
  {
    return count($this->cartItems);
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