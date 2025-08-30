<?php

namespace App\Livewire\Cart;

use Livewire\Component;
use App\Models\Product;
use App\Services\CartService;
use App\Traits\WithTryCatch;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class AddToCartButton extends Component
{
  use WithTryCatch;

  public $productId;
  public $class;
  public $svg;

  public function mount($productId, $class, $svg)
  {
    $this->productId = $productId;
    $this->class = $class;
    $this->svg = $svg;
  }

  public function addToCart(CartService $cartService, $quantity = 1)
  {
    $this->tryCatch(function () use ($quantity, $cartService) {
      $product = Product::findOrFail($this->productId);

      if (!auth()->user()) {
        return $cartService->addToGuestCart($product->id);
      }

      $cartService->add($product, $quantity, function ($product) use ($cartService) {
        $this->swalToast([
          "titleText" => $product->name,
          "text" => __('frontend.cart.added-to-cart'),
          "iconHtml" => $cartService->svgAddToCart,
          "customClass" => [
            "icon" => "border-0!"
          ]
        ]);

        $this->dispatch("added-to-cart");
      });
    }, [
      ModelNotFoundException::class => function ($e) {
        DB::rollBack();
        return to_route("home");
      },
      AuthorizationException::class => function ($e) {
        if (Gate::allows("view products")) {
          return to_route("admin.products.index");
        }
        return $this->swalError([
          "titleText" => "THIS ACTION IS UNAUTHORIZED!"
        ]);
      },
      Throwable::class => function ($e) {
        DB::rollBack();
        $this->swalTemplateSomethingWentWrong();
      }
    ]);
  }
  public function render()
  {
    return view('livewire.cart.add-to-cart-button');
  }
}
