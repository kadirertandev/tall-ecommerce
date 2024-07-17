<?php

namespace App\Livewire\Auth;

use App\Livewire\Forms\LoginForm as FormsLoginForm;
use App\Traits\CartService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LoginForm extends Component
{
  public FormsLoginForm $form;

  use CartService {
    CartService::addToCart as traitAddToCart;
  }

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
        $this->traitAddToCart(session()->get("guest_cart_product"));
        session()->remove("guest_cart_product");
      }
      DB::table("password_reset_tokens")->where("email", $this->form->email)->delete();
      auth()->user()->isAdmin() ? $this->redirectRoute("admin.dashboard") : $this->redirectRoute("home");
    }
    $this->addError('form.email', 'Invalid Credentials');
  }

  public function render()
  {
    return view('livewire.auth.login-form');
  }
}
