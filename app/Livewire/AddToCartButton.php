<?php

namespace App\Livewire;

use Livewire\Component;

class AddToCartButton extends Component
{
  public $productId;
  public $class;
  public $svg;

  public function mount($productId, $class, $svg)
  {
    $this->productId = $productId;
    $this->class = $class;
    $this->svg = $svg;
  }

  public function render()
  {
    return view('livewire.add-to-cart-button');
  }
}
