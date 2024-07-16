<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class AuthAvatarAtProfile extends Component
{
  #[On("user-profile-update")]
  public function render()
  {
    return view('livewire.auth-avatar-at-profile');
  }
}
