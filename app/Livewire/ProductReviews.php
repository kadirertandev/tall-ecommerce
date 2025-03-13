<?php

namespace App\Livewire;

use App\Models\ProductReview;
use App\Traits\WithRefreshFlowbite;
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
      return $this->dispatch("comment-error");
    } else {

      $validated = $this->validate();

      $this->tryCatch(function () use ($validated) {
        $this->authorize("canReview", $this->productId);

        $validated["rating"] = $this->rating ?? 0;
        $validated["user_id"] = auth()->user()->id;
        $validated["product_id"] = $this->productId;
        ProductReview::create($validated);

        $this->dispatch("comment-success");
        $this->resetPage();
        $this->reset(["rating", "title", "comment"]);
      }, [
        AuthorizationException::class => function ($e) {
          $this->dispatch("comment-error", title: "You can not evaluate this product", text: $e->getMessage());
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
