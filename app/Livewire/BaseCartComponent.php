<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Traits\CartService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class BaseCartComponent extends Component
{
  use CartService {
    CartService::addToCart as traitAddToCart;
    CartService::removeFromCart as traitRemoveFromCart;
    CartService::decreaseQuantity as traitDecreaseQuantity;
    CartService::increaseQuantity as traitIncreaseQuantity;
  }

  #[On("add-to-cart")]
  public function addToCart($productID)
  {
    return $this->traitAddToCart($productID);
  }

  #[On("remove-form-cart-modal-is-confirmed")]
  #[On("remove-form-cart-modal-is-denied")]
  public function removeFromCart($cartItemId, $addFavorites)
  {
    return $this->traitRemoveFromCart($cartItemId, $addFavorites);
  }

  public function decreaseQuantity($id)
  {
    return $this->traitDecreaseQuantity($id);
  }

  public function increaseQuantity($id)
  {
    return $this->traitIncreaseQuantity($id);
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
