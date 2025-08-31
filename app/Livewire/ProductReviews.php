<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductReview;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
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

  public $svgReview = '<svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" viewBox="0 0 24 24">
	<path fill="currentColor" d="M6 14h3.075L15.1 7.95l-3-3.075l-6.1 6.05zm6.05-5.1l-.95-.925l.975-.975l.925.95zM11.2 14H18v-2h-4.8zM2 22V2h20v16H6z" />
</svg>';

  public function mount($productId, $reviewCount)
  {
    $this->productId = $productId;
    $this->reviewCount = $reviewCount;

    if (session()->has("reviewId")) {
      $reviewId = session()->get("reviewId");
      $this->js("document.getElementById('review-" . $reviewId . "').style.backgroundColor = '#efefef'");
      session()->remove("reviewId");
    }
  }

  #[Computed()]
  public function reviews()
  {
    return Cache::remember("product_with_id_{$this->productId}_reviews_page_{$this->getPage()}", 60 * 5, function () {
      return ProductReview::with([
        "user" => fn($q) => $q->select(["id", "first_name", "last_name", "profile_image", "created_at"])
      ])
        ->withoutColumns(["updated_by", "updated_at", "deleted_at", "deleted_by"])
        ->where("product_id", $this->productId)
        ->where("status", \App\Enums\ReviewStatusType::APPROVED)
        ->latest()
        ->paginate(3);
    });
  }

  public function create()
  {
    if (!auth()->user()) {
      return $this->swalError([
        "titleText" => "Please log in.",
        "text" => "You can evaluate the product after logging in."
      ]);
    } else {

      $validated = $this->validate();

      $this->tryCatch(function () use ($validated) {
        $product = Product::findOrFail($this->productId);

        $this->authorize("canReview", $product);

        $validated["rating"] = $this->rating ?? 0;
        $validated["user_id"] = auth()->user()->id;
        $validated["product_id"] = $this->productId;
        ProductReview::create($validated);

        $this->swalSuccess([
          "titleText" => "Review submitted successfully!",
          "text" => "Your review will be visible after approval.",
          "iconHtml" => $this->svgReview,
          "customClass" => [
            "icon" => "border-0! text-gray-500!"
          ]
        ]);

        $this->resetPage();
        $this->reset(["rating", "title", "comment"]);
      }, [
        AuthorizationException::class => function ($e) {
          $this->swalError([
            "titleText" => "You can not evaluate this product",
            "text" => $e->getMessage()
          ]);
        }
      ]);
    }

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
