<?php

namespace App\Livewire\Cart;

use App\Models\Order;
use App\Models\OrderItem;
use App\Traits\CartData;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Component;
use Throwable;

class CartStepThree extends Component
{
  use CartData;
  use WithSweetAlert;
  use WithTryCatch;

  public function mount()
  {
    if (session()->has("selected-address-id")) {
      $this->selectedAddressId = session()->get("selected-address-id");
    }

    if ($this->cartItemsCount == 0) {
      $this->dispatch('set-cart-step', step: 1);
    }
  }

  public function giveOrder()
  {
    if (
      !$this->tryCatch(function () {
        $this->authorize("customer");
      })
    ) {
      return;
    }

    if ($this->cartItemsCount == 0) {
      return $this->dispatch('set-cart-step', step: 1);
    }


    $this->tryCatch(function () {
      DB::beginTransaction();

      $order = Order::create([
        "user_id" => auth()->user()->id,
        "city" => $this->selectedAddress->city,
        "district" => $this->selectedAddress->district,
        "neighborhood" => $this->selectedAddress->neighborhood,
        "address_line" => $this->selectedAddress->address_line,
      ]);

      foreach ($this->cartItems as $cartItem) {
        OrderItem::create([
          "order_id" => $order->id,
          "product_id" => $cartItem->product->id,
          "product_name" => $cartItem->product->name,
          "product_image" => $cartItem->product->image,
          "price" => $cartItem->totalPrice() / $cartItem->quantity,
          "original_product_price" => $cartItem->product->price,
          "quantity" => $cartItem->quantity,
        ]);
      }

      $this->cart->delete();

      DB::commit();

      session()->remove("cart_step");

      return to_route("auth.user.orders");
    }, [
      Throwable::class => function ($e) {
        DB::rollBack();
        $this->swalTemplateSomethingWentWrong();
      }
    ]);
  }

  public function render()
  {
    return view('livewire.cart.cart-step-three');
  }
}
