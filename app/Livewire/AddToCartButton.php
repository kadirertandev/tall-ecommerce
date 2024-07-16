<?php

namespace App\Livewire;




use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class AddToCartButton extends Component
{
  public $product_slug;
  public $class;
  public $svg;

  public function mount($product_slug, $class, $svg)
  {
    $this->product_slug = $product_slug;
    $this->class = $class;
    $this->svg = $svg;
  }

  #[Computed()]
  public function product()
  {
    return Product::where("slug", $this->product_slug)->first();
  }

  public function render()
  {
    return view('livewire.add-to-cart-button');
  }
}
