<?php

namespace App\Livewire\Admin;

use App\Enums\ReviewStatusType;
use App\Livewire\Forms\Admin\ReviewEditForm;
use App\Models\ProductReview;
use App\Traits\WithInteractModal;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithSoftDeleteFilter;
use App\Traits\WithSweetAlert;
use App\Traits\WithTableSortAndFilter;
use App\Traits\WithTryCatch;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class Reviews extends Component
{
  use WithPagination;
  use WithTryCatch;
  use WithRefreshFlowbite;
  use WithSweetAlert;
  use WithInteractModal;
  use WithTableSortAndFilter;
  use WithSoftDeleteFilter;

  public ReviewEditForm $editForm;

  public function handleSessionActions()
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

  public $statusFilter = [];
  public $columns = [
    "user_id" => "Customer",
    "product_id" => "Product",
    "title" => "Title",
    "comment" => "Comment",
    "rating" => "rating",
    "status" => "Status",
    "created_at" => "Review Date",
  ];


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

      $this->showModal("view-review");
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

      $this->showModal("edit-review");
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

      $this->selectedReview = $review;

      $this->closeModal("edit-review");

      $this->swalToast([
        "titleText" => "Review updated successfully!"
      ]);
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

  public function askDeleteReview($reviewId, $permanently = false)
  {
    $this->swalQuestion([
      "titleText" => "Are you sure you want to delete this review " . ($permanently ? "permanently?" : "?"),
      "confirmButtonText" => 'Yes',
      "denyButtonText" => "No",
      "onConfirm" => (!$permanently ? "" : "force-") . "delete-review-confirmed",
      "onConfirmParameters" => [
        "reviewId" => $reviewId
      ],
      "customClass" => [
        "title" => "text-nowrap!",
        "popup" => "min-w-max!"
      ]
    ]);
  }

  #[On("delete-review-confirmed")]
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

      $this->swalToast([
        "titleText" => "Review deleted successfully!"
      ]);
    }, [
      Throwable::class => function ($e) {
        if ($e->getCode() == 401) {
          return $this->swalError([
            "titleText" => "Can not delete nonrejected reviews."
          ]);
        }

        $this->swalTemplateSomethingWentWrong();
      }
    ]);
  }

  #[On("force-delete-review-confirmed")]
  public function forceDelete($reviewId)
  {
    $this->tryCatch(function () use ($reviewId) {
      if (!Gate::allows("force delete reviews")) {
        throw new UnauthorizedException("you cant delete review permanently");
      }

      ProductReview::withTrashed()->findOrFail($reviewId)->forceDelete();

      $this->swalToast([
        "titleText" => "Review deleted permanently successfully!"
      ]);
    });
  }

  public function restore($reviewId)
  {
    $this->tryCatch(function () use ($reviewId) {
      if (!Gate::allows("force delete reviews")) {
        throw new UnauthorizedException("you cant restore review");
      }

      ProductReview::withTrashed()->findOrFail($reviewId)->restore();

      $this->swalToast([
        "titleText" => "Review restored successfully!"
      ]);
    });
  }

  public function render()
  {
    return view('livewire.admin.reviews')
      ->layout("components.admin-layout", ["title" => "Reviews"])
      ->section("content");
  }
}
