<?php

namespace App\Traits;

use Livewire\Attributes\Computed;

trait UserAddressesData
{
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
}