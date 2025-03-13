<?php

namespace App\Livewire\Admin;

use App\Enums\ReviewStatusType;
use App\Livewire\Forms\Admin\ReviewEditForm;
use App\Models\ProductReview;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithTryCatch;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class Reviews extends Component
{
  use WithPagination;
  use WithTryCatch;
  use WithRefreshFlowbite;

  public ReviewEditForm $editForm;

  public function mount()
  {
    if (session()->has("review_id_to_edit")) {
      $this->showEditModal((int) session()->get("review_id_to_edit"), true);
      session()->remove("review_id_to_edit");
    }
  }

  public function boot()
  {
    $this->refreshFlobwite();
  }

  public $sortDir = "";
  public $sortBy = "";
  public $keyword = "";
  public $statusFilter = [];
  public $perPage = 10;
  public $columns = [
    "user_id" => "Customer",
    "product_id" => "Product",
    "title" => "Title",
    "comment" => "Comment",
    "rating" => "rating",
    "status" => "Status",
    "created_at" => "Review Date",
  ];

  #[Url()]
  public $withTrashed = false;
  public function updatedWithTrashed()
  {
    if ($this->withTrashed == true)
      $this->onlyTrashed = false;
  }

  #[Url()]
  public $onlyTrashed = false;
  public function updatedOnlyTrashed()
  {
    if ($this->onlyTrashed == true)
      $this->withTrashed = false;
  }

  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
  }

  #[Computed()]
  public function reviews()
  {
    return ProductReview::search($this->keyword)
      ->when($this->sortBy != "user_id" && $this->sortBy != "product_id", function ($query) {
        $query->when($this->sortBy && $this->sortDir, function ($query) {
          return $query->orderBy($this->sortBy, $this->sortDir);
        });
      })
      ->when($this->sortBy == "user_id", function ($query) {
        $query->join("users", "product_reviews.user_id", "=", "users.id")
          ->select("product_reviews.*", DB::raw("CONCAT(users.first_name, users.last_name) as user_full_name"))
          ->orderBy("user_full_name", $this->sortDir);
      })
      ->when($this->sortBy == "product_id", function ($query) {
        $query->join("products", "product_reviews.product_id", "=", "products.id")
          ->select("product_reviews.*", DB::raw("CONCAT(products.name, products.description, products.title) as product_full_text"))
          ->orderBy("product_full_text", $this->sortDir);
      })
      ->when($this->statusFilter, function ($query) {
        $query->whereIn("status", $this->statusFilter);
      })
      ->when($this->withTrashed, function ($query) {
        $query->withTrashed();
      })
      ->when($this->onlyTrashed, function ($query) {
        $query->onlyTrashed();
      })
      ->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  public $selectedReview;
  public function showViewModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->selectedReview = ProductReview::withTrashed()->findOrFail($id);

      $this->dispatch("open-review-view-modal");
    });
  }

  public function viewReviewOnPage($reviewId)
  {
    $this->tryCatch(function () use ($reviewId) {
      $review = ProductReview::findOrFail($reviewId);

      session()->put("reviewId", $review->id);

      $url = route("products.show", [
        "category_slug" => $review->product->category->slug,
        "product_slug" => $review->product->slug
      ]);

      $fragment = "#review-" . $review->id;
      $url .= $fragment;

      return redirect()->to(path: $url);
    });
  }

  public function showEditModal($id, $referred = false)
  {
    $this->tryCatch(function () use ($id, $referred) {
      if (!Gate::allows("edit reviews")) {
        throw new UnauthorizedException("can not edit review");
      }

      $review = ProductReview::findOrFail($id);

      $this->selectedReview = $review;
      $this->editForm->title = $review->title;
      $this->editForm->comment = $review->comment;

      $referred ? $this->dispatch("open-review-edit-modal-referred") :
        $this->dispatch("open-review-edit-modal");
    });
  }

  public function update()
  {
    $this->editForm->validate();

    $this->tryCatch(function () {
      if (!Gate::allows("edit reviews")) {
        throw new UnauthorizedException("can not edit review");
      }

      $review = ProductReview::findOrFail($this->selectedReview->id);

      $review->update([
        "title" => $this->editForm->title,
        "comment" => $this->editForm->comment,
        "updated_by" => auth()->user()->id,
        "updated_at" => Carbon::now(),
      ]);

      $this->dispatch("close-review-edit-modal");
      $this->dispatch("update_review_success");
    });
  }

  public function changeStatus($reviewId, $statusValue)
  {
    $this->tryCatch(function () use ($reviewId, $statusValue) {
      if (!Gate::allows("edit reviews")) {
        throw new UnauthorizedException("can not edit review");
      }

      ProductReview::findOrFail($reviewId)->update([
        "status" => ReviewStatusType::from($statusValue)->value
      ]);
    });
  }

  #[On("delete-review-modal-is-confirmed")]
  public function delete($reviewId)
  {
    $this->tryCatch(function () use ($reviewId) {
      if (!Gate::allows("delete reviews")) {
        throw new UnauthorizedException("can not delete review");
      }

      $review = ProductReview::findOrFail($reviewId);

      if ($review->status != ReviewStatusType::REJECTED) {
        throw new Exception(code: 401);
      }

      $review->delete();
      $review->update([
        "deleted_by" => auth()->user()->id
      ]);

      $this->dispatch("delete_review_success");
    }, [
      Throwable::class => function ($e) {
        if ($e->getCode() == 401) {
          return $this->dispatch("error-with-message", message: "Can not delete nonrejected reviews.", timer: 1500);
        }
        $this->dispatch("something-went-wrong");
      }
    ]);
  }

  #[On("force-delete-review-modal-is-confirmed")]
  public function forceDelete($reviewId)
  {
    $this->tryCatch(function () use ($reviewId) {
      if (!Gate::allows("force delete reviews")) {
        throw new UnauthorizedException("you cant delete review permanently");
      }

      ProductReview::withTrashed()->findOrFail($reviewId)->forceDelete();

      $this->dispatch("force-delete-review-success");
    });
  }

  public function restore($reviewId)
  {
    $this->tryCatch(function () use ($reviewId) {
      if (!Gate::allows("force delete reviews")) {
        throw new UnauthorizedException("you cant restore review");
      }

      ProductReview::withTrashed()->findOrFail($reviewId)->restore();
    });
  }

  public function render()
  {
    return view('livewire.admin.reviews')
      ->layout("components.admin-layout", ["title" => "Reviews"])
      ->section("content");
  }
}
