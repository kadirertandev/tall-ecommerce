<?php

namespace App\Livewire\Cart;

use App\Livewire\Forms\UserProfileAddressForm;
use App\Livewire\Shared\HandlesUserAddressCreation;
use App\Traits\AddressData;
use App\Traits\CartData;
use App\Traits\UserAddressesData;
use App\Traits\WithInteractModal;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Livewire\Attributes\On;

class CartStepTwo extends HandlesUserAddressCreation
{
  use WithTryCatch;
  use WithInteractModal;
  use WithSweetAlert;
  use CartData;
  use AddressData;
  use UserAddressesData;

  public UserProfileAddressForm $form;

  protected function getForm(): UserProfileAddressForm
  {
    return $this->form;
  }

  public function mount()
  {
    if (session()->has("selected-address-id")) {
      $this->selectedAddressId = session()->get("selected-address-id");
    }

    if ($this->cartItemsCount == 0) {
      $this->dispatch('set-cart-step', step: 1);
    }
  }

  public function next()
  {
    if ($this->selectedAddress) {
      $this->dispatch('set-cart-step', step: 3);
    } else {
      $this->addError("address-required", "Address Required");
    }
  }

  #[On("added-to-cart")]
  public function render()
  {
    return view('livewire.cart.cart-step-two');
  }
}
