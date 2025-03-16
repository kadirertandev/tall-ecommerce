<?php

namespace App\Livewire\Auth;

use App\Jobs\SendResetPasswordMail;
use App\Traits\WithTryCatch;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ForgotPassword extends Component
{
  use WithTryCatch;

  #[Validate("required|email|exists:users")]
  public $email;

  public function resetPassword()
  {
    $this->validate();

    $this->tryCatch(function () {
      $token = Str::random(64);

      DB::table("password_reset_tokens")->insert([
        "email" => $this->email,
        "token" => $token,
        "created_at" => Carbon::now()
      ]);

      dispatch(new SendResetPasswordMail($token, $this->email));
      session()->put("reset-password-mail-sent", true);
      to_route("login");
    });
  }

  public function render()
  {
    return view('livewire.auth.forgot-password');
  }
}
