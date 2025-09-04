<?php

namespace App\Livewire\UserProfile;

use App\DTOs\ProductReview\NewProductReviewDto;
use App\Livewire\Forms\ProductReviewForm;
use App\Models\Order;
use App\Models\Product;
use App\Services\ProductReviewService;
use App\Traits\WithInteractModal;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Illuminate\Auth\Access\AuthorizationException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class UserProfileOrders extends Component
{
  use WithPagination;
  use WithTryCatch;
  use WithSweetAlert;
  use WithInteractModal;

  public ProductReviewForm $reviewForm;

  public $svgReview = '<svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" viewBox="0 0 24 24">
	<path fill="currentColor" d="M6 14h3.075L15.1 7.95l-3-3.075l-6.1 6.05zm6.05-5.1l-.95-.925l.975-.975l.925.95zM11.2 14H18v-2h-4.8zM2 22V2h20v16H6z" />
</svg>';

  public $perPage = 6;

  #[Computed()]
  public function orders()
  {
    return Order::with([
      "user",
      "orderItems" => fn($q) => $q->with([
        "product" => fn($q) => $q->with([
          "category" => fn($q) => $q->select(["id", "name", "slug"])->without("brands"),
          "brand" => fn($q) => $q->select(["id", "name", "slug"])
        ])->select(["id", "name", "slug", "image", "category_id", "brand_id"])
      ])
    ])
      ->where("user_id", auth()->user()->id)
      ->withSubTotal()
      ->withCustomerName()
      ->latest()
      ->simplePaginate($this->perPage);
  }

  public $rating = 0;
  public $productToComment;
  public function openReviewModalForProduct($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->productToComment = Product::findOrFail($id);

      $this->showModal("user-profile-order-product-review");
    });
  }

  public function createProductReview(ProductReviewService $productReviewService)
  {
    $this->tryCatch(function () use ($productReviewService) {
      $validated = $this->reviewForm->validate();

      $this->authorize("canReview", $this->productToComment);

      $newProductReviewDto = NewProductReviewDto::fromArray([
        ...$validated,
        "rating" => $this->rating ?? 0,
        "userId" => auth()->user()->id,
        "productId" => $this->productToComment->id
      ]);

      $productReviewService->create($newProductReviewDto);

      $this->swalSuccess([
        "titleText" => "Review submitted successfully!",
        "text" => "Your review will be visible after approval.",
        "iconHtml" => $this->svgReview,
        "customClass" => [
          "icon" => "border-0! text-gray-500!"
        ]
      ]);

      $this->closeModal("user-profile-order-product-comment");
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
    return view('livewire.user-profile.user-profile-orders')
      ->layout("components.profile-layout", ["title" => "Orders"])
      ->section("content");
  }
}
