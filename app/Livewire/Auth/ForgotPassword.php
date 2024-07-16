<?php

namespace App\Livewire\Auth;

use App\Jobs\SendResetPasswordMail;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ForgotPassword extends Component
{
  #[Validate("required|email|exists:users")]
  public $email;

  public function rendered()
  {
    if (session()->has("reset-password-mail-sent")) {
      $this->dispatch("reset-password-mail-sent");
      session()->remove("reset-password-mail-sent");
    }
  }

  public function resetPassword()
  {
    $this->validate();

    $token = Str::random(64);

    DB::table("password_reset_tokens")->insert([
      "email" => $this->email,
      "token" => $token,
      "created_at" => Carbon::now()
    ]);

    dispatch(new SendResetPasswordMail($token, $this->email));
    session()->put("reset-password-mail-sent", true);
    to_route("login");
  }

  public function render()
  {
    return view('livewire.auth.forgot-password');
  }
}
