<?php

namespace App\Livewire;

use App\Livewire\Forms\UserProfileUpdateForm as FormsUserProfileUpdateForm;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserProfileUpdateForm extends Component
{
  use WithFileUploads;
  public FormsUserProfileUpdateForm $form;

  public $userID;
  #[Computed()]
  public function user(): User
  {
    return User::find($this->userID);
  }
  public function mount()
  {
    $this->userID = auth()->user()->id;
    $this->form->first_name = auth()->user()->first_name;
    $this->form->last_name = auth()->user()->last_name;
    $this->form->email = auth()->user()->email;
    $this->form->date_of_birth = auth()->user()->date_of_birth;
    $this->form->phone_number = auth()->user()->phone_number;
  }

  public function update()
  {
    // dd($this->form->date_of_birth);
    $validated = $this->form->validate();
    if ($validated["profile_image"]) {
      if ($this->user->profile_image && Storage::disk("public")->exists($this->user->profile_image)) {
        Storage::disk("public")->delete($this->user->profile_image);
      }
      $validated["profile_image"] = $validated["profile_image"]->store("profile_images", "public");
    } else {
      $validated["profile_image"] = $this->user->profile_image;
    }
    $this->user->update($validated);
    // $this->form->deneme();
    // dd($this->form->all());
    $this->dispatch("user-profile-update", $this->form->all());
  }

  public function render()
  {
    return view('livewire.user-profile-update-form');
  }
}
