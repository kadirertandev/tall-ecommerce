<?php

namespace App\Livewire;

use Livewire\Component;

class UserProfileReviews extends Component
{
  public function render()
  {
    return view('livewire.user-profile-reviews')
      ->layout("components.profile-layout", ["title" => "Reviews"])
      ->section("content");
  }
}
