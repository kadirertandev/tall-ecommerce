<?php

namespace App\Livewire;

use App\Livewire\Forms\ProductReviewForm;
use App\Models\Product;
use App\Models\ProductReview;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class UserProfileOrders extends Component
{
  use WithTryCatch;
  use WithSweetAlert;

  public ProductReviewForm $reviewForm;

  public $svgReview = '<svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" viewBox="0 0 24 24">
	<path fill="currentColor" d="M6 14h3.075L15.1 7.95l-3-3.075l-6.1 6.05zm6.05-5.1l-.95-.925l.975-.975l.925.95zM11.2 14H18v-2h-4.8zM2 22V2h20v16H6z" />
</svg>';

  #[Computed()]
  public function orders()
  {
    return auth()->user()->orders;
  }

  public $rating = 0;
  public $productToComment;
  public function openCommentModalForProduct($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->productToComment = Product::findOrFail($id);

      $this->reviewForm->resetErrorBag();
      $this->reset("rating");
      $this->dispatch("open-user-profile-order-product-comment-modal");
    });
  }

  public function createComment()
  {
    $validated = $this->reviewForm->validate();

    $this->tryCatch(function () use ($validated) {
      $this->authorize("canReview", $this->productToComment);

      $validated["rating"] = $this->rating ?? 0;
      $validated["user_id"] = auth()->user()->id;
      $validated["product_id"] = $this->productToComment->id;

      ProductReview::create($validated);

      $this->swalSuccess([
        "titleText" => "Review submitted successfully!",
        "text" => "Your review will be visible after approval.",
        "iconHtml" => $this->svgReview,
        "customClass" => [
          "icon" => "border-0! text-gray-500!"
        ]
      ]);

      $this->reset("rating");
      $this->reviewForm->reset();
    }, [
      AuthorizationException::class => function ($e) {
        $this->swalError([
          "titleText" => "You can not evaluate this product",
          "text" => $e->getMessage()
        ]);
      }
    ]);
  }

  public function render()
  {
    return view('livewire.user-profile-orders');
  }
}
