<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UserProfileChangePasswordForm extends Component
{
  #[Validate("required")]
  public $currentPassword;
  #[Validate("required|min:8")]
  public $newPassword;

  #[Computed()]
  public function user()
  {
    return auth()->user();
  }

  public function changePassword()
  {
    $this->validate();
    if (!Hash::check($this->currentPassword, $this->user->password)) {
      return $this->addError("currentPassword", __("frontend.form.change-password-form.old-password-dismatch"));
    }

    $this->user->update([
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
