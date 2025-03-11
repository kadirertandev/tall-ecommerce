<?php

declare(strict_types=1);

namespace App\Traits;

use App\Livewire\Forms\UserProfileAddressForm;
use Livewire\Attributes\Computed;
use App\Models\UserAddress;

trait Addresses
{
  use WithTryCatch;

  public UserProfileAddressForm $form;

  #[Computed()]
  public function data()
  {
    return json_decode(file_get_contents(storage_path("app/public/address.json")), (bool) JSON_PRETTY_PRINT);
  }
  #[Computed()]
  public function cities()
  {
    $cities = [];
    foreach ($this->data as $value) {
      $cities[] = $value["name"];
    }
    return $cities;
  }
  #[Computed()]
  public function districts()
  {
    $districts = [];
    foreach ($this->data as $value) {
      if ($value["name"] == $this->form->selectedCity) {
        foreach ($value["counties"] as $countie) {
          $districts[] = $countie["name"];
        }
      }
    }
    return $districts;
  }
  #[Computed()]
  public function neighborhoods()
  {
    $neighborhoods = [];
    foreach ($this->data as $value) {
      if ($value["name"] == $this->form->selectedCity) {
        foreach ($value["counties"] as $countie) {
          if ($countie["name"] == $this->form->selectedDistrict) {
            foreach ($countie["districts"] as $district) {
              foreach ($district["neighborhoods"] as $neighborhood) {
                $neighborhoods[] = $neighborhood["name"];
              }
            }
          }
        }
      }
    }
    return $neighborhoods;
  }

  public function add()
  {
    $this->tryCatch(function () {
      $validated = $this->form->validate();

      # if makeDefault checkbox is checked
      # set all existing addresses of user to non-default before making selected address the default
      if ($this->form->makeDefault) {
        UserAddress::where("user_id", auth()->user()->id)
          ->update(["is_default" => 0]);
      }

      $userAddress = UserAddress::create([
        "user_id" => auth()->user()->id,
        "title" => $validated["addressTitle"],
        "city" => $validated["selectedCity"],
        "district" => $validated["selectedDistrict"],
        "neighborhood" => $validated["selectedNeighborhood"],
        "address_line" => $validated["addressLine"],
        "is_default" => $this->form->makeDefault
      ]);

      $this->dispatch("close-address-modal");
      $this->form->reset();
    });
  }
}