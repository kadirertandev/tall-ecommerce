<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class Cart extends Component
{
  /* #[Url(keep: true)] */
  public $step = 1;
  #[On("set-cart-step")]
  public function setStep($step)
  {
    $this->step = $step;
  }

  public function mount()
  {
    // dd(Cache::has('weeklyDealProducts'));
    // dd(Cache::getStore());
  }

  #[Computed()]
  public function lastViewedProducts()
  {
    $lastViewedProductIDs = Session::get("last_viewed_products", []);
    if (count($lastViewedProductIDs) > 0) {
      return Product::whereIn("id", array_keys($lastViewedProductIDs))->get();
    }
    return [];
  }

  public function clearLastViewedProducts()
  {
    Session::remove("last_viewed_products");
    return to_route("auth.user.cart");
  }

  public function render()
  {
    return view('livewire.cart')->layout("components.layout");
  }
}
