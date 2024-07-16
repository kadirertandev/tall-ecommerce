<?php

namespace App\Traits;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Throwable;

trait AddToCart
{
  // #[On("add-to-cart")]
  public function addToCart2($productID)
  {
    try {
      $product = Product::findOrFail($productID);
      if (!auth()->user()) {
        session()->put("guest_cart_product", $productID);
        return to_route("login");
      }

      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }

      if (!auth()->user()->cart) {
        $cart = Cart::create([
          "user_id" => auth()->user()->id
        ]);
      } else {
        $cart = auth()->user()->cart;
      }

      if ($cart->products()->contains($product["id"])) {
        // dd((int) $product["discount_amount"], $product["price"], $product["price"] - (float) $product["discount_amount"]);
        $item = CartItem::where("product_id", $product["id"])->first();
        $item->update([
          "quantity" => $item->quantity += 1,
          "discount_amount" => $item->discount_amount + $product["discount_amount"],
          "item_total_price" => $item->quantity * ($product["price"] - (float) $product["discount_amount"])
        ]);
      } else {
        // dd((int) $product["discount_amount"], $product["price"]);
        CartItem::create([
          "cart_id" => $cart->id,
          "product_id" => $product["id"],
          "quantity" => 1,
          "price" => $product["price"] /* - (float) $product["discount_amount"] */ ,
          "discount_amount" => $product["discount_amount"],
          "item_total_price" => $product["price"] - (float) $product["discount_amount"],
        ]);
      }

      $this->dispatch("added-to-cart", product: $product, text: __('frontend.cart.added-to-cart'));
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Product not found!");
    } catch (AuthorizationException $e) {
      $this->dispatch("error-with-message", message: $e->getMessage());
    } catch (Throwable $e) {
      // dd($e->getMessage());
      $this->dispatch("something-went-wrong");
    }
  }
}