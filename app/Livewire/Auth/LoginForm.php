<?php

namespace App\Livewire\Auth;

use App\Livewire\BaseCartComponent;
use App\Livewire\Forms\LoginForm as FormsLoginForm;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Traits\AddToCart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Livewire\Component;

class LoginForm extends Component
{
  public FormsLoginForm $form;
  use AddToCart;
  public function mount()
  {
    if (session()->has("reset-password-mail-sent")) {
      $this->dispatch("reset-password-mail-sent");
      session()->remove("reset-password-mail-sent");
    }
    if (session()->has("reset-password-success")) {
      $this->dispatch("reset-password-success");
      session()->remove("reset-password-success");
    }
    if (session()->has("change-password-success")) {
      $this->dispatch("change-password-success");
      session()->remove("change-password-success");
    }
  }

  public function login()
  {
    $validated = $this->form->validate();
    if (auth()->attempt($validated, (bool) $this->form->remember_me)) {
      session()->regenerate();
      if (session()->has("guest_cart_product")) {
        // dd("user added item to cart as guest");
        // $this->addToCart(session()->get("guest_cart_product"));
        // $this->cartService->addToCart(session()->get("guest_cart_product"));
        $this->addToCart2(session()->get("guest_cart_product"));
        session()->remove("guest_cart_product");
      }
      DB::table("password_reset_tokens")->where("email", $this->form->email)->delete();
      auth()->user()->isAdmin() ? $this->redirectRoute("admin.dashboard") : $this->redirectRoute("home");
    }
    $this->addError('form.email', 'Invalid Credentials');
  }

  /* public function addToCart(Product $product)
  {
    $cart = null;

    if (auth()->user()->cart) {
      $cart = auth()->user()->cart;
    } else {
      $cart = Cart::create([
        "user_id" => auth()->user()->id
      ]);
    }

    if ($cart->products()->contains($product["id"])) {
      $item = CartItem::where("cart_id", $cart->id)->where("product_id", $product["id"])->first();
      $item->update([
        "quantity" => $item->quantity += 1,
        "item_total_price" => ($item->quantity) * $product["price"]
      ]);
    } else {
      CartItem::create([
        "cart_id" => $cart->id,
        "product_id" => $product["id"],
        "quantity" => 1,
        "price" => $product["price"],
        "item_total_price" => $product["price"],
      ]);
    }

    session()->remove("guest_cart_product");
  } */

  public function render()
  {
    return view('livewire.auth.login-form');
  }
}
