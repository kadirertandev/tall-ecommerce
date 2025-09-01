<?php

namespace App\Livewire\UserProfile;

use App\DTOs\UserAddress\UpdateUserAddressDto;
use App\DTOs\UserAddress\UserAddressForm;
use App\Livewire\Forms\UserProfileAddressForm;
use App\Livewire\Shared\HandlesUserAddressCreation;
use App\Models\UserAddress;
use App\Services\UserAddressService;
use App\Traits\AddressData;
use App\Traits\UserAddressesData;
use App\Traits\WithInteractModal;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Livewire\Attributes\On;

class UserProfileAddresses extends HandlesUserAddressCreation
{
  use WithTryCatch;
  use WithSweetAlert;
  use WithInteractModal;
  use AddressData;
  use UserAddressesData;

  public $selectedAddressId;

  public UserProfileAddressForm $form;

  protected function getForm(): UserProfileAddressForm
  {
    return $this->form;
  }

  public function edit($id)
  {
    $this->tryCatch(function () use ($id) {
      $address = UserAddress::findOrFail($id);

      $this->authorize("update", $address);

      $this->selectedAddressId = $id;

      $this->form = UserAddressForm::fillFromModel($this->form, $address);

      $this->showModal("edit-address");
    });
  }

  public function update(UserAddressService $userAddressService)
  {
    $validated = $this->form->validate();

    $this->tryCatch(function () use ($validated, $userAddressService) {
      $address = UserAddress::findOrFail($this->selectedAddressId);

      $this->authorize("update", $address);

      $newUserAddressDto = UpdateUserAddressDto::fromArray(
        [
          "userId" => auth()->user()->id,
          ...$validated
        ]
      );

      $userAddressService->update($address, $newUserAddressDto);

      $this->closeModal("edit-address");
      $this->dispatch("address-updated");
      $this->swalToast([
        "titleText" => "Address updated successfully!"
      ]);
      $this->form->reset();
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
  public function delete(UserAddressService $userAddressService, $addressId)
  {
    $this->tryCatch(function () use ($userAddressService, $addressId) {
      $address = UserAddress::findOrFail($addressId);

      $this->authorize("delete", $address);

      $userAddressService->delete($address);

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
    return view('livewire.user-profile.user-profile-addresses')
      ->layout("components.profile-layout", ["title" => "Addresses"])
      ->section("content");
  }
}
