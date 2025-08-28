<?php

namespace App\Livewire\Cart;

use App\Exceptions\CartItemQuantityReachedMinimumException;
use App\Services\CartService;
use App\Traits\WithTryCatch;
use Livewire\Component;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class DecreaseQuantityButton extends Component
{
  use WithTryCatch;

  public $cartItemId;

  public function mount($cartItemId)
  {
    $this->cartItemId = $cartItemId;
  }

  public function decreaseQuantity(CartService $cartService)
  {
    $this->tryCatch(function () use ($cartService) {
      $cartService->decreaseQuantity($this->cartItemId);

      $this->dispatch("refresh-cart");
    }, [
      AuthorizationException::class => function ($e) {
        if (Gate::allows("view products")) {
          return to_route("admin.products.index");
        }
        return $this->swalError([
          "titleText" => "THIS ACTION IS UNAUTHORIZED!"
        ]);
      },
      CartItemQuantityReachedMinimumException::class => function ($e) {
        $this->dispatch("ask-remove-from-cart", cartItemId: $this->cartItemId);
      }
    ]);
  }

  public function render()
  {
    return view('livewire.cart.decrease-quantity-button');
  }
}
