<?php

namespace App\Livewire\Auth;

use App\Events\Login;
use App\Livewire\Forms\LoginForm as FormsLoginForm;
use App\Traits\CartActions;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LoginForm extends Component
{
  use CartActions {
    CartActions::addToCart as traitAddToCart;
  }

  public FormsLoginForm $form;

  private $svgEmail = '<svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" viewBox="0 0 16 16">
	<path fill="none" stroke="currentColor" stroke-linejoin="round" d="m5 4l4.5 3L14 4M2 8.5h5m-4 2h5m-3.5 2h10v-9h-10v3H1" stroke-width="1" />
</svg>';

  public function checkForAlerts()
  {
    if (session()->has("reset-password-mail-sent")) {
      $this->swalSuccess([
        "titleText" => "Check Your Inbox",
        "text" => "We have sent you password reset email!",
        "showConfirmButton" => true,
        "timer" => false,
        "iconHtml" => $this->svgEmail,
        "customClass" => [
          "icon" => "border-0!"
        ]
      ]);

      session()->remove("reset-password-mail-sent");
    }

    if (session()->has("reset-password-success")) {
      $this->swalSuccess([
        "titleText" => "Password Reset Successful!"
      ]);

      session()->remove("reset-password-success");
    }

    if (session()->has("change-password-success")) {
      $this->swalSuccess([
        "titleText" => "Change Password Successful!"
      ]);

      session()->remove("change-password-success");
    }
  }

  public function login()
  {
    $validated = $this->form->validate();
    if (auth()->attempt($validated, (bool) $this->form->remember_me)) {
      session()->regenerate();
      Login::dispatch();
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
