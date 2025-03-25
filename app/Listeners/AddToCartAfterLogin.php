<?php

namespace App\Listeners;

use App\Traits\CartActions;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Livewire\Component;

class AddToCartAfterLogin extends Component
{
  use CartActions {
    CartActions::syncCart as traitSyncCart;
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
    if (session()->has("guest_cart_products")) {
      $this->traitSyncCart(session()->get("guest_cart_products"));

      session()->remove("guest_cart_products");
    }
  }
}
