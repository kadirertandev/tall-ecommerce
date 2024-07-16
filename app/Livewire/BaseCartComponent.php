<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class BaseCartComponent extends Component
{
  #[On("add-to-cart")]
  public function addToCart($productID)
  {
    try {
      $product = Product::find($productID);
      if (!auth()->user()) {
        session()->put("guest_cart_product", $product);
        return to_route("login");
      }

      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }

      if (!$this->cart) {
        $cart = Cart::create([
          "user_id" => $this->user["id"]
        ]);
      } else {
        $cart = $this->cart;
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
    } catch (AuthorizationException $e) {
      $this->dispatch("error-with-message", message: $e->getMessage());
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  #[On("remove-form-cart-modal-is-confirmed")]
  #[On("remove-form-cart-modal-is-denied")]
  public function removeFromCart($cartItemId, $addFavorites)
  {
    // dd($cartItemId);
    // dd($cartItemId, $cartItem, $addFavorites);
    try {
      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }
      $cartItem = CartItem::find((int) $cartItemId);
      if (!empty($cartItem)) {
        if ($addFavorites) {
          if (!$this->user->favorites->contains($cartItem->product->id)) {
            $this->user->favorites()->attach($cartItem->product->id);
          }
          $cartItem->delete();
          $this->dispatch("removed-from-cart-and-added-favorites", product: $cartItem->product, text: __('frontend.cart.removed-from-cart-and-added-to-favorites'));
        } else {
          $cartItem->delete();
          $this->dispatch("removed-from-cart", product: $cartItem->product, text: __('frontend.cart.removed-from-cart'));
        }
      }
      $this->dispatch("refresh-cart");
    } catch (AuthorizationException $e) {
      $this->dispatch("error-with-message", message: $e->getMessage());
    } catch (Throwable $e) {
      dd($e->getMessage(), "remove from cart");
      $this->dispatch("something-went-wrong");
    }
  }

  /* #[On("decrease-quantity")] */
  public function decreaseQuantity($id)
  {
    // dd($id);
    try {
      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }
      $cartItem = CartItem::find($id);
      if (!empty($cartItem)) {
        if ($cartItem->quantity > 1) {
          $cartItem->decrement("quantity", 1);
          $cartItem->update([
            // "item_total_price" => $cartItem->quantity * $cartItem->price
            "item_total_price" => $cartItem->quantity * ($cartItem->product->price - (float) $cartItem->product->discount_amount)
          ]);
        } else {
          #2 seçenek, evet-sil, sil ve favorilere ekle
          $this->dispatch("remove-from-cart-modal", cartItemId: $cartItem->id);
          // $cartItem->delete();
          // $this->dispatch("removed-from-cart", product: $cartItem->product, text: __('frontend.cart.removed-from-cart'));
        }
        $this->dispatch("refresh-cart");
      }
    } catch (AuthorizationException $e) {
      $this->dispatch("error-with-message", message: $e->getMessage());
    } catch (Throwable $e) {
      dd($e->getMessage(), "decrease quantity");
      $this->dispatch("something-went-wrong");
    }
  }

  /* #[On("increase-quantity")] */
  public function increaseQuantity($id)
  {
    // dd($id);
    try {
      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }
      $cartItem = CartItem::find($id);
      if (!empty($cartItem)) {
        $cartItem->increment("quantity", 1);
        $cartItem->update([
          // "item_total_price" => $cartItem->quantity * $cartItem->price
          "item_total_price" => $cartItem->quantity * ($cartItem->product->price - (float) $cartItem->product->discount_amount)
        ]);
        $this->dispatch("refresh-cart");
      }
    } catch (AuthorizationException $e) {
      $this->dispatch("error-with-message", message: $e->getMessage());
    } catch (Throwable $e) {
      // dd($e->getMessage());
      $this->dispatch("something-went-wrong");
    }
  }

  #[Computed()]
  public function user()
  {
    return auth()->user();
  }

  #[Computed()]
  public function cart()
  {
    return $this->user->cart;
  }

  #[Computed()]
  public function cartItems()
  {
    return $this->cart->items ?? [];
  }

  #[Computed()]
  public function cartItemsCount()
  {
    return count($this->cartItems);
  }

  #[Computed()]
  public function cartProducts()
  {
    return $this->cart->products() ?? [];
  }

  #[Computed()]
  public function addresses()
  {
    return $this->user->addresses;
  }

  #[Computed()]
  public function defaultAddress()
  {
    return $this->user->defaultAddress();
  }

  #[Computed()]
  public function nonDefaultAddresses()
  {
    return $this->addresses->where("is_default", 0)->all();
  }

  public $selectedAddress;

  public function updatedSelectedAddress()
  {
    // dd("selected address updated");
    session()->put("selected-address-for-cart", $this->selectedAddress);
  }

  public function clearSession()
  {
    session()->remove("selected-address-for-cart");
  }

  public function showSelectedAddress()
  {
    dd($this->selectedAddress);
  }

  #[Computed()]
  public function finalAddress()
  {
    return UserAddress::find($this->selectedAddress);
  }

  public function giveOrder()
  {
    try {
      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }
    } catch (AuthorizationException $e) {
      $this->dispatch("error-with-message", message: $e->getMessage());
    }

    if ($this->cartItemsCount == 0) {
      session()->remove("selected-address-for-cart");
      $this->dispatch('set-cart-step', step: 1);
    } else {
      // dd($this->cartItems, $this->selectedAddress);
      try {
        $order = Order::create([
          "user_id" => $this->user->id,
          "city" => $this->finalAddress->city,
          "district" => $this->finalAddress->district,
          "neighborhood" => $this->finalAddress->neighborhood,
          "address_line" => $this->finalAddress->address_line,
          "total_price" => $this->cart->subtotal()
        ]);
        if ($order) {
          foreach ($this->cartItems as $cartItem) {
            OrderItem::create([
              "order_id" => $order->id,
              "product_id" => $cartItem->product->id,
              "price" => $cartItem->price,
              "quantity" => $cartItem->quantity,
              "item_total_price" => $cartItem->item_total_price,
            ]);
          }

          $this->cart->delete();
          return to_route("auth.user.orders");
        }
      } catch (Throwable $e) {
        $this->dispatch("something-went-wrong", exception: $e);
      }
    }
  }

  public function render()
  {
    return view('livewire.base-cart-component');
  }
}
