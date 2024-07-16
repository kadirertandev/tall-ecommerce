<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class UserProfileAddressForm extends Form
{
  #[Validate("required|min:3")]
  public $addressTitle = "";
  #[Validate("required")]
  public $selectedCity = "";
  #[Validate("required")]
  public $selectedDistrict = "";
  #[Validate("required")]
  public $selectedNeighborhood = "";
  #[Validate("required|min:10")]
  public $addressLine = "";
  public $makeDefault = false;
}
