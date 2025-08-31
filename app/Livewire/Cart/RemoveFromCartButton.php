<?php

namespace App\Livewire\Cart;

use App\Helpers\IconHelper;
use App\Models\CartItem;
use App\Services\CartService;
use App\Services\UserProductFavoriteService;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class RemoveFromCartButton extends Component
{
  use WithSweetAlert;
  use WithTryCatch;

  public $cartItemId;
  public $type;

  public function mount($cartItemId, $type = "nav")
  {
    $this->cartItemId = $cartItemId;
    $this->type = $type;
  }

  #[On("ask-remove-from-cart")]
  public function askRemoveFromCart($cartItemId = null)
  {
    $id = $cartItemId ?? $this->cartItemId;

    $this->swalQuestion([
      "titleText" => "Are you sure you want to remove this product from cart?",
      "confirmButtonText" => 'Yes',
      "denyButtonText" => "Remove and add to favorites",
      "onConfirm" => "remove-product-from-cart-confirmed-{$id}",
      "onDeny" => "remove-product-from-cart-denied-{$id}",
      "onConfirmParameters" => [
        "cartItemId" => $id,
        "addToFavorites" => false
      ],
      "onDenyParameters" => [
        "cartItemId" => $id,
        "addToFavorites" => true
      ],
    ]);
  }

  #dynamic events
  #without dynamic events X listeners on page will call same event X times and cause unexpected errors such as ModelNotFoundException
  public function getListeners(): array
  {
    return [
      "remove-product-from-cart-confirmed-{$this->cartItemId}" => 'removeFromCart',
      "remove-product-from-cart-denied-{$this->cartItemId}" => 'removeFromCart',
    ];
  }

  public function removeFromCart(CartService $cartService, UserProductFavoriteService $userProductFavoriteService, $cartItemId, $addToFavorites)
  {
    $this->tryCatch(
      function () use ($cartService, $userProductFavoriteService, $cartItemId, $addToFavorites) {
        if (Gate::denies("customer")) {
          throw new AuthorizationException();
        }

        $cartItem = CartItem::findOrFail($cartItemId);

        $this->authorize("delete", $cartItem);

        $cartService->remove($cartItem);

        if ($addToFavorites) {
          $userProductFavoriteService->add($cartItem->product->id);

          $this->dispatch("added-to-favorites");

          $this->swalToast([
            "titleText" => $cartItem->product->name,
            "text" => __('frontend.cart.removed-from-cart-and-added-to-favorites'),
            "iconHtml" => IconHelper::$svgRemoveFromCartAddToFavorites,
            "customClass" => [
              "icon" => "border-0! text-red-500!"
            ]
          ]);
        } else {
          $this->swalToast([
            "titleText" => $cartItem->product->name,
            "text" => __('frontend.cart.removed-from-cart'),
            "iconHtml" => IconHelper::$svgRemoveFromCart,
            "customClass" => [
              "icon" => "border-0! text-red-500!"
            ],
          ]);
        }

        $this->dispatch("refresh-cart");
      },
      [
        AuthorizationException::class => function ($e) {
          if (Gate::allows("view products")) {
            return to_route("admin.products.index");
          }
          return $this->swalError([
            "titleText" => $e->getMessage()
          ]);
        }
      ]
    );
  }

  public function render()
  {
    return view('livewire.cart.remove-from-cart-button');
  }
}
