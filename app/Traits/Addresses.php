<?php

declare(strict_types=1);

namespace App\Traits;

use App\Livewire\Forms\UserProfileAddressForm;
use Livewire\Attributes\Computed;
use App\Models\UserAddress;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Throwable;

trait Addresses
{
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
    try {
      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }

      $validated = $this->form->validate();
      #before creating a new address
      #if makeDefault is true, make is_default field false for all existing addresses in database
      if ($this->form->makeDefault) {
        UserAddress::where("user_id", auth()->user()->id)
          ->where("is_default", 1)
          ->update(["is_default" => 0]);
      }
      $userAddress = UserAddress::create([
        "user_id" => auth()->user()->id,
        "title" => $validated["addressTitle"],
        "city" => $validated["selectedCity"],
        "district" => $validated["selectedDistrict"],
        "neighborhood" => $validated["selectedNeighborhood"],
        "address_line" => $validated["addressLine"],
        "is_default" => (count(auth()->user()->addresses) == 0) ? true : $this->form->makeDefault
      ]);
      $this->dispatch("address-created");
      $this->dispatch("close-address-modal");
      $this->form->reset();
    } catch (AuthorizationException $e) {
      $this->dispatch("error-with-message", message: $e->getMessage());
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }

  }
}