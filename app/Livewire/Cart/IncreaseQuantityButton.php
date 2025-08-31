<?php

namespace App\Livewire\Cart;

use App\Traits\WithTryCatch;
use Livewire\Component;
use App\Services\CartService;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\CartItem;
use Illuminate\Support\Facades\Gate;

class IncreaseQuantityButton extends Component
{
  use WithTryCatch;

  public $cartItemId;

  public function mount($cartItemId)
  {
    $this->cartItemId = $cartItemId;
  }

  public function increaseQuantity(CartService $cartService)
  {
    $this->tryCatch(function () use ($cartService) {
      $this->authorize("customer");

      $cartItem = CartItem::findOrFail($this->cartItemId);

      $this->authorize("update", $cartItem);

      $cartService->increaseQuantity($cartItem);

      $this->dispatch("refresh-cart");
    }, [
      AuthorizationException::class => function ($e) {
        if (Gate::allows("view products")) {
          return to_route("admin.products.index");
        }
        return $this->swalError([
          "titleText" => $e->getMessage()
        ]);
      }
    ]);
  }
  public function render()
  {
    return view('livewire.cart.increase-quantity-button');
  }
}
