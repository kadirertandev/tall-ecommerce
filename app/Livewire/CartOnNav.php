<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Error;
use Exception;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class CartOnNav extends BaseCartComponent
{
  #[On("added-to-cart")]
  public function render()
  {
    return view('livewire.cart-on-nav');
  }
}
