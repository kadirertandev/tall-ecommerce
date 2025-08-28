<?php

namespace App\Listeners;

use App\Services\CartService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Livewire\Component;

class AddToCartAfterLogin extends Component
{
  /**
   * Create the event listener.
   */
  public function __construct(private CartService $cartService)
  {
    //
  }

  /**
   * Handle the event.
   */
  public function handle(object $event): void
  {
    if (session()->has("guest_cart_products")) {
      $this->cartService->syncCart(session()->get("guest_cart_products"));

      session()->remove("guest_cart_products");
    }
  }
}
