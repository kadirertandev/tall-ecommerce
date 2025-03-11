<?php

namespace App\Livewire;

use App\Livewire\Forms\ProductReviewForm;
use App\Models\Product;
use App\Models\ProductReview;
use App\Traits\WithTryCatch;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class UserProfileOrders extends Component
{
  use WithTryCatch;
  public ProductReviewForm $reviewForm;

  #[Computed()]
  public function user()
  {
    return auth()->user();
  }

  #[Computed()]
  public function orders()
  {
    return $this->user->orders;
  }

  public $rating = 0;
  public $productToComment;
  public function openCommentModalForProduct($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->productToComment = Product::findOrFail($id);

      $this->dispatch("open-user-profile-order-product-comment-modal");
    });
  }
  public function createComment()
  {
    $this->tryCatch(function () {
      $this->authorize("canReview", $this->productToComment);

      $validated = $this->reviewForm->validate();
      $validated["rating"] = $this->rating ?? 0;
      $validated["user_id"] = $this->user->id;
      $validated["product_id"] = $this->productToComment->id;

      ProductReview::create($validated);

      $this->reset("rating");
      $this->reviewForm->reset();
      $this->dispatch("close-user-profile-order-product-comment-modal");
    }, [
      AuthorizationException::class => function ($e) {
        $this->dispatch("comment-error", title: "You can not evaluate this product", text: $e->getMessage());
      }
    ]);
  }

  #[On("user-profile-order-product-comment-modal-closed")]
  public function addressModalClosed()
  {
    $this->reviewForm->resetErrorBag();
    $this->reset("rating");
    $this->dispatch("comment-success");
  }

  public function render()
  {
    return view('livewire.user-profile-orders');
  }
}
