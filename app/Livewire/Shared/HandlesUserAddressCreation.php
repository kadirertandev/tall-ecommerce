<?php

namespace App\Livewire\Shared;

use App\DTOs\UserAddress\NewUserAddressDto;
use App\Livewire\Forms\UserProfileAddressForm;
use App\Services\UserAddressService;
use App\Traits\WithInteractModal;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Livewire\Component;

abstract class HandlesUserAddressCreation extends Component
{
  use WithTryCatch;
  use WithSweetAlert;
  use WithInteractModal;

  abstract protected function getForm(): UserProfileAddressForm;

  public function create(UserAddressService $userAddressService)
  {
    $validated = $this->getForm()->validate();

    $this->tryCatch(function () use ($validated, $userAddressService) {
      $newUserAddressDto = NewUserAddressDto::fromArray(
        [
          "userId" => auth()->user()->id,
          ...$validated
        ]
      );

      $userAddressService->create($newUserAddressDto);

      $this->closeModal("new-address");

      $this->swalToast([
        "titleText" => "Address created successfully!"
      ]);

      $this->getForm()->reset();
    });
  }
}
