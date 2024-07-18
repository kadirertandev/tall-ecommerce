<?php

namespace App\Listeners;

use App\Traits\CartService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Livewire\Component;

class AddToCartAfterLogin extends Component
{
  use CartService {
    CartService::addToCart as traitAddToCart;
  }
  /**
   * Create the event listener.
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   */
  public function handle(object $event): void
  {
    if (session()->has("guest_cart_product")) {
      $this->traitAddToCart(session()->get("guest_cart_product"));
      session()->remove("guest_cart_product");
    }
  }
}
