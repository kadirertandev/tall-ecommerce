<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductReview;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Illuminate\Auth\Access\AuthorizationException;
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
  public $rating;
  #[Rule("required|min:5|max:50")]
  public $title;
  #[Rule("required|min:5|max:200")]
  public $comment;
  public $svgReview = '<svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" viewBox="0 0 24 24">
	<path fill="currentColor" d="M6 14h3.075L15.1 7.95l-3-3.075l-6.1 6.05zm6.05-5.1l-.95-.925l.975-.975l.925.95zM11.2 14H18v-2h-4.8zM2 22V2h20v16H6z" />
</svg>';

  public function mount($productId)
  {
    $this->productId = $productId;

    if (session()->has("reviewId")) {
      $reviewId = session()->get("reviewId");
      $this->js("document.getElementById('review-" . $reviewId . "').style.backgroundColor = '#efefef'");
      session()->remove("reviewId");
    }
  }

  #[Computed()]
  public function reviews()
  {
    return ProductReview::where("product_id", $this->productId)->where("status", \App\Enums\ReviewStatusType::APPROVED)->latest()->paginate(1);
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
