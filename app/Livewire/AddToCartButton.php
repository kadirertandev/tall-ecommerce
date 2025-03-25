<?php

namespace App\Livewire;

use App\Traits\CartActions;
use Livewire\Component;

class AddToCartButton extends Component
{
  use CartActions {
    CartActions::addToCart as traitAddToCart;
  }

  public $productId;
  public $class;
  public $svg;

  public function mount($productId, $class, $svg)
  {
    $this->productId = $productId;
    $this->class = $class;
    $this->svg = $svg;
  }

  public function addToCart()
  {
    $this->traitAddToCart($this->productId);
  }

  public function render()
  {
    return view('livewire.add-to-cart-button');
  }
}
