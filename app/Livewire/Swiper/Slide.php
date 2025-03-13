<?php

namespace App\Livewire\Swiper;

use Livewire\Attributes\Locked;
use Livewire\Component;

class Slide extends Component
{
  #[Locked()]
  public $product;
  public $prefix;

  public function mount($product, $prefix)
  {
    $this->product = $product;
    $this->prefix = $prefix . '-';
  }
  public function render()
  {
    return view('livewire.swiper.slide');
  }
}
