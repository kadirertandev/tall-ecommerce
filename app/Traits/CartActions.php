<?php

namespace App\Traits;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Throwable;

trait CartActions
{
  use WithTryCatch;
  use CartData;
  use WithSweetAlert;

  #[On("add-to-cart")]
  public function addToCart($productID)
  {
    $this->tryCatch(function () use ($productID) {
      $product = Product::findOrFail($productID);

      if (!auth()->user()) {
        session()->put("guest_cart_product", $product->id);
        return to_route("login");
      }

      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }

      DB::beginTransaction();

      $cart = auth()->user()->cart()->firstOrCreate([
        "user_id" => auth()->user()->id
      ]);

      if ($cart->products()->contains($product["id"])) {
        $item = CartItem::where("product_id", $product["id"])->firstOrFail();
        $item->increment("quantity", 1);
        $item->update([
          "discount_amount" => $item->discount_amount + $product["discount_amount"],
          "item_total_price" => $item->quantity * ($product["price"] - (float) $product["discount_amount"])
        ]);
      } else {
        CartItem::create([
          "cart_id" => $cart->id,
          "product_id" => $product["id"],
          "quantity" => 1,
          "price" => $product["price"],
          "discount_amount" => $product["discount_amount"],
          "item_total_price" => $product["price"] - (float) $product["discount_amount"],
        ]);
      }

      DB::commit();

      $this->swalToast([
        "titleText" => $product->name,
        "text" => __('frontend.cart.added-to-cart'),
        "iconHtml" => $this->svgAddToCart,
        "customClass" => [
          "icon" => "border-0!"
        ]
      ]);
    }, [
      ModelNotFoundException::class => function ($e) {
        DB::rollBack();
        return to_route("home");
      },
      AuthorizationException::class => function ($e) {
        return to_route("admin.products.index");
      },
      Throwable::class => function ($e) {
        DB::rollBack();
        $this->swalTemplateSomethingWentWrong();
      }
    ]);
  }

  public function askRemoveFromCart($cartItemId)
  {
    $this->swalQuestion([
      "titleText" => "Are you sure you want to remove this product from cart?",
      "confirmButtonText" => 'Yes',
      "denyButtonText" => "Remove and add to favorites",
      "onConfirm" => "remove-product-from-cart-confirmed",
      "onDeny" => "remove-product-from-cart-denied",
      "onConfirmParameters" => [
        "cartItemId" => $cartItemId,
        "addToFavorites" => false
      ],
      "onDenyParameters" => [
        "cartItemId" => $cartItemId,
        "addToFavorites" => true
      ],
    ]);
  }

  #[On("remove-product-from-cart-confirmed")]
  #[On("remove-product-from-cart-denied")]
  public function removeFromCart($cartItemId, $addToFavorites)
  {
    $this->tryCatch(
      function () use ($cartItemId, $addToFavorites) {
        if (Gate::denies("customer")) {
          throw new AuthorizationException("This action is unauthorized!");
        }

        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->delete();

        if ($addToFavorites) {
          if (!auth()->user()->favorites->contains($cartItem->product->id)) {
            auth()->user()->favorites()->attach($cartItem->product->id);
          }

          $this->swalToast([
            "titleText" => $cartItem->product->name,
            "text" => __('frontend.cart.removed-from-cart-and-added-to-favorites'),
            "iconHtml" => $this->svgRemoveFromCartAddToFavorites,
            "customClass" => [
              "icon" => "border-0! text-red-500!"
            ]
          ]);
        } else {
          $this->swalToast([
            "titleText" => $cartItem->product->name,
            "text" => __('frontend.cart.removed-from-cart'),
            "iconHtml" => $this->svgRemoveFromCart,
            "customClass" => [
              "icon" => "border-0! text-red-500!"
            ],
          ]);
        }

        $this->dispatch("refresh-cart");
      },
      [
        AuthorizationException::class => function ($e) {
          return to_route("admin.products.index");
        }
      ]
    );
  }

  public function decreaseQuantity($id)
  {
    $this->tryCatch(function () use ($id) {
      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }

      $cartItem = CartItem::findOrFail($id);

      if ($cartItem->quantity > 1) {
        $cartItem->decrement("quantity", 1);
        $cartItem->update([
          "item_total_price" => $cartItem->quantity * ($cartItem->product->price - (float) $cartItem->product->discount_amount)
        ]);
      } else {
        $this->askRemoveFromCart($cartItem->id);
      }

      $this->dispatch("refresh-cart");
    }, [
      AuthorizationException::class => function ($e) {
        return to_route("admin.products.index");
      }
    ]);
  }

  public function increaseQuantity($id)
  {
    $this->tryCatch(function () use ($id) {
      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }

      $cartItem = CartItem::findOrFail($id);

      $cartItem->increment("quantity", 1);
      $cartItem->update([
        "item_total_price" => $cartItem->quantity * ($cartItem->product->price - (float) $cartItem->product->discount_amount)
      ]);

      $this->dispatch("refresh-cart");
    }, [
      AuthorizationException::class => function ($e) {
        return to_route("admin.products.index");
      }
    ]);
  }
}