<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UserProfileChangePasswordForm extends Component
{
  #[Validate("required")]
  public $currentPassword;
  #[Validate("required|min:8")]
  public $newPassword;

  public function changePassword()
  {
    $this->validate();
    if (!Hash::check($this->currentPassword, auth()->user()->password)) {
      return $this->addError("currentPassword", __("frontend.form.change-password-form.old-password-dismatch"));
    }

    auth()->user()->update([
      "password" => Hash::make($this->newPassword)
    ]);

    auth()->logout();

    session()->invalidate();
    session()->regenerateToken();

    session()->put("change-password-success", true);
    to_route("login");
  }
  public function render()
  {
    return view('livewire.user-profile-change-password-form');
  }
}
