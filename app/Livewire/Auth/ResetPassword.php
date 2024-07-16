<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ResetPassword extends Component
{
  #[Validate("required|email|exists:users")]
  public $email;
  #[Validate("required|min:8|confirmed")]
  public $password;
  #[Validate("same:password")]
  public $password_confirmation;
  public $token;

  public function mount($token)
  {
    $resetRow = DB::table("password_reset_tokens")->where([
      "token" => $token
    ])->pluck("created_at")->first();
    if (!$resetRow) {
      to_route("login");
    }
    $this->token = $token;
  }

  public function resetPassword()
  {
    $this->validate();

    $rowExists = DB::table("password_reset_tokens")->where([
      "email" => $this->email,
      "token" => $this->token
    ])->first();

    if ($rowExists) {
      User::where("email", $this->email)->update([
        "password" => Hash::make($this->password)
      ]);

      DB::table("password_reset_tokens")->where([
        "email" => $this->email,
        "token" => $this->token
      ])->delete();

      session()->put("reset-password-success", true);
      to_route("login");
    } else {
      $this->dispatch("something-went-wrong");
    }
  }
  public function render()
  {
    return view('livewire.auth.reset-password');
  }
}
