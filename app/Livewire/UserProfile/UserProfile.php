<?php

namespace App\Livewire\UserProfile;

use App\Livewire\Forms\UserProfileUpdateForm;
use App\Traits\WithSweetAlert;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserProfile extends Component
{
  use WithFileUploads;
  use WithSweetAlert;

  public UserProfileUpdateForm $form;

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

    $validated["updated_at"] = now();

    auth()->user()->update($validated);

    $this->dispatch("user-profile-update");

    $this->swalSuccess([
      "titleText" => "Profile updated!"
    ]);
  }

  public function askDeleteAccount()
  {
    $this->swalQuestion([
      "titleText" => "Are you sure you want to delete your account?",
      "text" => "Once the account deletion is accepted, your account will be permanently deleted after 7 days and cannot be retrieved after this period.",
      "confirmButtonText" => 'Confirm',
      "denyButtonText" => "Cancel",
      "onConfirm" => "delete-account-confirmed",
      "customClass" => [
        "icon" => "hidden!",
        "htmlContainer" => "text-sm"
      ]
    ]);
  }

  #[On("delete-account-confirmed")]
  public function delete()
  {
    auth()->user()->update([
      "delete_request" => true,
      "delete_request_at" => now()
    ]);

    $this->swalSuccess([
      "titleText" => "Account deletion request sent!",
      "text" => "You can cancel deletion request in 7 days.",
      "timer" => false,
      "showConfirmButton" => true,
      "confirmButtonText" => "Okay",
      "customClass" => [
        "htmlContainer" => "text-sm"
      ]
    ]);
  }

  public function revertDeleteAccount()
  {
    auth()->user()->update([
      "delete_request" => false,
      "delete_request_at" => null
    ]);

    $this->swalSuccess([
      "titleText" => "Account deletion request canceled!",
      "timer" => false,
      "showConfirmButton" => true,
      "confirmButtonText" => "Okay"
    ]);
  }

  public function render()
  {
    return view('livewire.user-profile.user-profile')
      ->layout("components.profile-layout", ["title" => "Profile"])
      ->section("content");
  }
}
