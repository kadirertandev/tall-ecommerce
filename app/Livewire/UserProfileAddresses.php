<?php

namespace App\Livewire;

use App\Livewire\Forms\UserProfileAddressForm;
use App\Models\UserAddress;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class UserProfileAddresses extends Component
{
  public UserProfileAddressForm $form;

  #[Computed()]
  public function data()
  {
    return json_decode(file_get_contents(storage_path("app/public/address.json")), JSON_PRETTY_PRINT);
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

  #[Computed()]
  public function addresses()
  {
    return auth()->user()->addresses;
  }

  #[Computed()]
  public function defaultAddress()
  {
    return auth()->user()->defaultAddress();
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

  public $selectedAddressId;
  public function edit($id)
  {
    try {
      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }

      $address = UserAddress::find($id);
      $this->selectedAddressId = $id;
      $this->form->addressTitle = $address->title;
      $this->form->selectedCity = $address->city;
      $this->form->selectedDistrict = $address->district;
      $this->form->selectedNeighborhood = $address->neighborhood;
      $this->form->addressLine = $address->address_line;
      $this->form->makeDefault = (bool) $address->is_default;
      $this->dispatch('open-address-modal', name: 'edit-address');
    } catch (AuthorizationException $e) {
      $this->dispatch("error-with-message", message: $e->getMessage());
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }
  public function update()
  {
    try {
      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }

      $address = UserAddress::find($this->selectedAddressId);
      $validated = $this->form->validate();
      if ($this->form->makeDefault) {
        UserAddress::where("user_id", auth()->user()->id)
          ->update(["is_default" => 0]);
      }
      $address->update([
        "title" => $validated["addressTitle"],
        "city" => $validated["selectedCity"],
        "district" => $validated["selectedDistrict"],
        "neighborhood" => $validated["selectedNeighborhood"],
        "address_line" => $validated["addressLine"],
        "is_default" => $this->form->makeDefault
      ]);
      $this->dispatch("address-updated");
      $this->dispatch("close-address-modal");
    } catch (AuthorizationException $e) {
      $this->dispatch("error-with-message", message: $e->getMessage());
    } catch (Throwable $e) {
      $this->dispatch("close-address-modal");
      $this->dispatch("something-went-wrong");
    }
  }
  #[On("address-modal-closed")]
  public function addressModalClosed()
  {
    $this->form->resetErrorBag();
    $this->form->reset();
  }

  #[On("delete-address-modal-is-confirmed")]
  public function delete($addressId)
  {
    try {
      if (Gate::denies("customer")) {
        throw new AuthorizationException("This action is unauthorized!");
      }

      $address = UserAddress::find($addressId);
      if ($address->is_default) {
        // session()->remove("selected-address-for-cart");
      }
      $address->delete();
      $this->dispatch("address-deleted");
    } catch (AuthorizationException $e) {
      $this->dispatch("error-with-message", message: $e->getMessage());
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  #[On("address-created")]
  #[On("address-updated")]
  #[On("address-deleted")]
  public function render()
  {
    return view('livewire.user-profile-addresses');
  }
}
