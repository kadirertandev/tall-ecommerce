<?php

namespace App\Livewire\Swiper;

use Livewire\Attributes\Locked;
use Livewire\Component;

class Slide extends Component
{
  #[Locked()]
  public $deal;
  public $prefix;

  public function mount($deal, $prefix)
  {
    $this->deal = $deal;
    $this->prefix = $prefix . '-';
  }
  public function render()
  {
    return view('livewire.swiper.slide');
  }
}
