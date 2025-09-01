<?php

namespace App\DTOs\UserAddress;

use App\Livewire\Forms\UserProfileAddressForm;
use App\Models\UserAddress;

class UserAddressForm
{
  public static function fillFromModel(UserProfileAddressForm $userProfileAddressForm, UserAddress $userAddress)
  {
    $userProfileAddressForm->addressTitle = $userAddress->title;
    $userProfileAddressForm->selectedCity = $userAddress->city;
    $userProfileAddressForm->selectedDistrict = $userAddress->district;
    $userProfileAddressForm->selectedNeighborhood = $userAddress->neighborhood;
    $userProfileAddressForm->addressLine = $userAddress->address_line;
    $userProfileAddressForm->makeDefault = (bool) $userAddress->is_default;

    return $userProfileAddressForm;
  }
}