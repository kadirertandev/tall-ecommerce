<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;

class ProductShow extends Component
{
  public $slug;
  public function mount($slug)
  {
    $this->slug = $slug;
  }

  #[Computed()]
  public function products()
  {

  }
  public function render()
  {
    return view('livewire.product-show')->layout("components.layout");
  }
}
