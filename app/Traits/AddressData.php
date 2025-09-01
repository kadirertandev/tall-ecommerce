<?php

namespace App\Traits;

use App\Services\AddressDataService;
use Livewire\Attributes\Computed;

trait AddressData
{
  private AddressDataService $addressDataService;

  public function boot(AddressDataService $addressDataService)
  {
    $this->addressDataService = $addressDataService;
  }

  #[Computed()]
  public function data()
  {
    return $this->addressDataService->getData();
  }

  #[Computed()]
  public function cities()
  {
    return $this->addressDataService->cities();
  }

  #[Computed()]
  public function districts()
  {
    return $this->addressDataService->districts($this->form->selectedCity);
  }

  #[Computed()]
  public function neighborhoods()
  {
    return $this->addressDataService->neighborhoods(
      city: $this->form->selectedCity,
      district: $this->form->selectedDistrict
    );
  }
}