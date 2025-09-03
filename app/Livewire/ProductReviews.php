<?php

namespace App\Livewire;

use App\DTOs\ProductReview\NewProductReviewDto;
use App\Helpers\IconHelper;
use App\Models\Product;
use App\Models\ProductReview;
use App\Services\ProductReviewService;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ProductReviews extends Component
{
  use WithPagination;
  use WithTryCatch;
  use WithRefreshFlowbite;
  use WithSweetAlert;

  public function boot()
  {
    $this->refreshFlobwite();
  }

  public $productId;
  public $reviewCount;

  public $rating;

  #[Rule("required|min:5|max:50")]
  public $title;

  #[Rule("required|min:5|max:200")]
  public $comment;

  #[Url(as: "reviews-page", keep: true)]
  public $reviewsPage = 1;

  public function updatedReviewsPage($newReviewsPage)
  {
    $this->reviewsPage = $newReviewsPage;
  }

  public $highlightReviewId = null;

  public function mount($productId, $reviewCount)
  {
    $this->productId = $productId;
    $this->reviewCount = $reviewCount;

    if (session()->has("reviewId")) {
      $this->highlightReviewId = session()->get("reviewId");

      $this->changeReviewsPageForHighlightedReview();

      session()->remove("reviewId");
    }
  }

  public function changeReviewsPageForHighlightedReview()
  {
    if (is_null($this->highlightReviewId)) {
      return;
    }

    $review = ProductReview::where("product_id", $this->productId)
      ->where("status", \App\Enums\ReviewStatusType::APPROVED)
      ->find($this->highlightReviewId);

    #decides which reviews page to navigate and highlights review
    if ($review) {
      $newerReviewsCount = ProductReview::where("product_id", $this->productId)
        ->where("status", \App\Enums\ReviewStatusType::APPROVED)
        ->where("created_at", ">", $review->created_at)
        ->count();

      $perPage = 3;
      $page = (int) floor($newerReviewsCount / $perPage) + 1;

      $this->setPage(page: $page, pageName: "reviews-page");
      $this->reviewsPage = $page;

      // $this->js("document.getElementById('review-" . $this->highlightReviewId . "').style.backgroundColor = '#efefef'");
    }
  }

  #[Computed()]
  public function reviews()
  {
    return Cache::remember("product_with_id_{$this->productId}_reviews_page_{$this->getPage(pageName: 'reviews-page')}", 60 * 5, function () {
      return ProductReview::with([
        "user" => fn($q) => $q->select(["id", "first_name", "last_name", "profile_image", "created_at"])
      ])
        ->withoutColumns(["updated_by", "updated_at", "deleted_at", "deleted_by"])
        ->where("product_id", $this->productId)
        ->where("status", \App\Enums\ReviewStatusType::APPROVED)
        ->latest()
        ->paginate(3, pageName: 'reviews-page');
    });
  }

  public function create(ProductReviewService $productReviewService)
  {
    $validated = $this->validate();

    $this->tryCatch(function () use ($validated, $productReviewService) {
      if (!auth()->check()) {
        throw new AuthorizationException(message: 'guest error', code: 401);
      }

      $product = Product::findOrFail($this->productId);

      $this->authorize("canReview", $product);

      $newProductReviewDto = NewProductReviewDto::fromArray([
        ...$validated,
        "rating" => $this->rating ?? 0,
        "userId" => auth()->user()->id,
        "productId" => $this->productId
      ]);

      $productReviewService->create($newProductReviewDto);

      $this->swalSuccess([
        "titleText" => "Review submitted successfully!",
        "text" => "Your review will be visible after approval.",
        "iconHtml" => IconHelper::$svgReview,
        "customClass" => [
          "icon" => "border-0! text-gray-500!"
        ]
      ]);

      $this->resetPage();
      $this->reset(["rating", "title", "comment"]);
    }, [
      AuthorizationException::class => function ($e) {
        if ($e->getCode() === 401 && $e->getMessage() === "guest error") {
          return $this->swalError([
            "titleText" => "Please log in.",
            "text" => "You can evaluate the product after logging in."
          ]);
        }

        $this->swalError([
          "titleText" => "You can not evaluate this product",
          "text" => $e->getMessage()
        ]);
      }
    ]);
  }

  public function edit($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->authorize("edit reviews");

      $review = ProductReview::findOrFail($id);

      session()->put("review_id_to_edit", $review->id);

      return to_route("admin.reviews.index");
    });
  }

  public function render()
  {
    return view('livewire.product-reviews');
  }
}
