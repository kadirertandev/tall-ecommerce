<?php

namespace App\Traits;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

trait CartService
{
  use WithTryCatch;

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

      $this->dispatch("added-to-cart", product: $product, text: __('frontend.cart.added-to-cart'));
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
        $this->dispatch("something-went-wrong");
      }
    ]);
  }

  public function removeFromCart($cartItemId, $addFavorites)
  {
    $this->tryCatch(
      function () use ($cartItemId, $addFavorites) {
        if (Gate::denies("customer")) {
          throw new AuthorizationException("This action is unauthorized!");
        }

        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->delete();

        if ($addFavorites) {
          if (!auth()->user()->favorites->contains($cartItem->product->id)) {
            auth()->user()->favorites()->attach($cartItem->product->id);
          }
          $this->dispatch("removed-from-cart-and-added-favorites", product: $cartItem->product, text: __('frontend.cart.removed-from-cart-and-added-to-favorites'));
        } else {
          $this->dispatch("removed-from-cart", product: $cartItem->product, text: __('frontend.cart.removed-from-cart'));
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
        $this->dispatch("remove-from-cart-modal", cartItemId: $cartItem->id);
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