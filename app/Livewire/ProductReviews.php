<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\ProductReview;
use Throwable;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ProductReviews extends Component
{
  use WithPagination;
  public $page = 1;
  public function updatedPage()
  {
    $this->dispatch("product-reviews-page-updated");
  }

  public $product_slug;
  public $rating;
  #[Rule("required|min:5|max:50")]
  public $title;
  #[Rule("required|min:5|max:200")]
  public $comment;
  public function mount($product_slug)
  {
    $this->product_slug = $product_slug;
    if (session()->has("reviewId")) {
      $reviewId = session()->get("reviewId");
      $this->js("document.getElementById('review-" . $reviewId . "').style.backgroundColor = '#efefef'");
      session()->remove("reviewId");
    }
  }

  public function boot()
  {
    if ($this->product() == null) {
      $this->skipRender();
    }
  }

  #[Computed()]
  public function product()
  {
    return Product::where("slug", $this->product_slug)->first();
  }

  #[Computed()]
  public function reviews()
  {
    return ProductReview::where("product_id", $this->product()->id)->where("status", \App\Enums\ReviewStatusType::APPROVED)->latest()->paginate(4);
  }

  #[Computed()]
  public function reviewsCount()
  {
    return $this->reviews()->count();
  }

  public function create()
  {
    if (!auth()->user()) {
      return $this->dispatch("comment-error");
    } else {
      try {
        $this->authorize("canReview", $this->product);
        $validated = $this->validate();
        $validated["rating"] = $this->rating ?? 0;
        $validated["user_id"] = auth()->user()->id;
        $validated["product_id"] = $this->product()->id;
        ProductReview::create($validated);
        $this->resetPage();
        $this->reset(["rating", "title", "comment"]);
        $this->dispatch("comment-success");
      } catch (AuthorizationException $e) {
        $this->dispatch("comment-error", title: "You can not evaluate this product", text: $e->getMessage());
      }
    }

  }

  public function edit($id)
  {
    try {
      $review = ProductReview::findOrFail($id);
      session()->put("review_id_to_edit", $review->id);
      return to_route("admin.reviews.index");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Review not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function delete($reviewId)
  {
    try {
      ProductReview::findOrFail($reviewId)->delete();
    } catch (Exception $e) {

    }
  }

  public function render()
  {
    return view('livewire.product-reviews');
  }
}
