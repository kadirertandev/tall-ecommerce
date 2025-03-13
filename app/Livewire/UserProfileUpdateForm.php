<?php

namespace App\Livewire;

use App\Livewire\Forms\UserProfileUpdateForm as FormsUserProfileUpdateForm;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserProfileUpdateForm extends Component
{
  use WithFileUploads;
  public FormsUserProfileUpdateForm $form;

  public function mount()
  {
    $this->form->first_name = auth()->user()->first_name;
    $this->form->last_name = auth()->user()->last_name;
    $this->form->email = auth()->user()->email;
    $this->form->date_of_birth = auth()->user()->date_of_birth;
    $this->form->phone_number = auth()->user()->phone_number;
  }

  public function update()
  {
    $validated = $this->form->validate();

    if ($validated["profile_image"]) {
      if (auth()->user()->profile_image && Storage::disk("public")->exists(auth()->user()->profile_image)) {
        Storage::disk("public")->delete(auth()->user()->profile_image);
      }
      $validated["profile_image"] = $validated["profile_image"]->store("profile_images", "public");
    } else {
      $validated["profile_image"] = auth()->user()->profile_image;
    }

    auth()->user()->update($validated);

    $this->dispatch("user-profile-update", $this->form->all());
  }

  public function render()
  {
    return view('livewire.user-profile-update-form');
  }
}
