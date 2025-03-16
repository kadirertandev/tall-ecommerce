<?php

namespace App\Livewire;

use App\Livewire\Forms\UserProfileAddressForm;
use App\Models\UserAddress;
use App\Traits\Addresses;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class UserProfileAddresses extends Component
{
  use Addresses;
  use WithTryCatch;
  use WithSweetAlert;

  public UserProfileAddressForm $form;

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

  public $selectedAddressId;
  public function edit($id)
  {
    $this->tryCatch(function () use ($id) {
      $address = UserAddress::findOrFail($id);

      $this->selectedAddressId = $id;
      $this->form->addressTitle = $address->title;
      $this->form->selectedCity = $address->city;
      $this->form->selectedDistrict = $address->district;
      $this->form->selectedNeighborhood = $address->neighborhood;
      $this->form->addressLine = $address->address_line;
      $this->form->makeDefault = (bool) $address->is_default;

      $this->dispatch('open-address-modal', name: 'edit-address');
    });
  }
  public function update()
  {
    $validated = $this->form->validate();

    $this->tryCatch(function () use ($validated) {
      $address = UserAddress::findOrFail($this->selectedAddressId);

      # if makeDefault checkbox is checked
      # set all existing addresses of user to non-default before making selected address the default
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

      $this->swalToast([
        "titleText" => "Address updated successfully!"
      ]);

      $this->dispatch("address-updated");
      $this->dispatch("close-address-modal");
    });
  }

  #[On("address-modal-closed")]
  public function addressModalClosed()
  {
    $this->form->resetErrorBag();
    $this->form->reset();
  }

  public function askDeleteAddress($addressId)
  {
    $this->swalQuestion([
      "titleText" => "Are you sure you want to delete this address?",
      "text" => null,
      "confirmButtonText" => 'Yes',
      "denyButtonText" => "No",
      "onConfirm" => "delete-address-confirmed",
      "onConfirmParameters" => [
        "addressId" => $addressId
      ],
      "customClass" => [
        "title" => "text-nowrap",
        "popup" => "min-w-max"
      ]
    ]);
  }

  #[On("delete-address-confirmed")]
  public function delete($addressId)
  {
    $this->tryCatch(function () use ($addressId) {
      $address = UserAddress::findOrFail($addressId);

      $address->delete();

      $this->swalToast([
        "titleText" => "Address deleted successfully!"
      ]);

      $this->dispatch("address-deleted");
    });
  }

  #[On("address-created")]
  #[On("address-updated")]
  #[On("address-deleted")]
  public function render()
  {
    return view('livewire.user-profile-addresses');
  }
}
