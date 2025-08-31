<?php

namespace App\Services;

use App\Exceptions\CartItemQuantityReachedMinimumException;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class CartService
{
  use AuthorizesRequests;

  public function add(Product $product, $quantity, \Closure|null $callback = null)
  {
    $this->authorize("customer");

    DB::beginTransaction();

    $cart = auth()->user()->cart()->firstOrCreate();

    if ($cart->items()->where("product_id", $product->id)->exists()) {
      $item = CartItem::where("product_id", $product->id)
        ->where("cart_id", $cart->id)
        ->firstOrFail();
      $item->increment("quantity", $quantity);
    } else {
      CartItem::create([
        "cart_id" => $cart->id,
        "product_id" => $product->id,
        "quantity" => $quantity
      ]);
    }

    DB::commit();

    if (is_callable($callback))
      $callback($product);
  }

  public function addToGuestCart($productId)
  {
    $guestCart = session()->get("guest_cart_products", []);

    if (!key_exists($productId, $guestCart)) {
      $guestCart[$productId] = [
        "product_id" => $productId,
        "quantity" => 1
      ];
    } else {
      $guestCart[$productId]["quantity"] += 1;
    }

    session()->put("guest_cart_products", $guestCart);

    return to_route("login");
  }

  public function syncCart($guestCartProducts)
  {
    foreach ($guestCartProducts as $productId => $data) {
      $product = Product::find($productId);

      if ($product) {
        $this->add($product, $data["quantity"]);
      }
    }
  }

  public function remove(CartItem $cartItem)
  {
    $cartItem->delete();
  }

  public function increaseQuantity(CartItem $cartItem)
  {
    $cartItem->increment("quantity", 1);
  }

  public function decreaseQuantity(CartItem $cartItem)
  {
    if ($cartItem->quantity > 1) {
      $cartItem->decrement("quantity", 1);
    } else {
      throw new CartItemQuantityReachedMinimumException("Quantity reached minimum");
    }
  }
}