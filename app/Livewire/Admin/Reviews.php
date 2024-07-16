<?php

namespace App\Livewire\Admin;

use App\Enums\ReviewStatusType;
use App\Livewire\Forms\Admin\ReviewEditForm;
use App\Models\ProductReview;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
  public $page = 1;
  public function updatedPage()
  {
    $this->dispatch("refresh-flowbite");
  }

  public ReviewEditForm $editForm;

  public $sortDir = "";
  public $sortBy = "";
  public $keyword = "";
  public function updatedKeyword()
  {
    $this->dispatch("refresh-flowbite");
  }
  public $statusFilter = [];
  public function updatedStatusFilter()
  {
    $this->dispatch("refresh-flowbite");
  }
  public $perPage = 10;
  public function updatedPerPage()
  {
    $this->dispatch("refresh-flowbite");
  }
  public $withTrashed = false;
  public $onlyTrashed = false;
  public function updatedWithTrashed()
  {
    $this->withTrashed == true ? $this->onlyTrashed = false : "";
    $this->dispatch("refresh-flowbite");

  }
  public function updatedOnlyTrashed()
  {
    $this->onlyTrashed == true ? $this->withTrashed = false : "";
    $this->dispatch("refresh-flowbite");
  }

  public function mount()
  {
    if (session()->has("review_id_to_edit")) {
      $this->showEditModal((int) session()->get("review_id_to_edit"), true);
      session()->remove("review_id_to_edit");
    }
  }

  #[Computed()]
  public function reviewsTemplate()
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
      });
  }

  #[Computed()]
  public function reviews()
  {
    return $this->reviewsTemplate->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  #[Computed()]
  public function columns()
  {
    return [
      "user_id" => "Customer",
      "product_id" => "Product",
      "title" => "Title",
      "comment" => "Comment",
      "rating" => "rating",
      "status" => "Status",
      "created_at" => "Review Date",
    ];
  }

  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
    $this->dispatch("refresh-flowbite");
  }

  public $selectedReview;
  public function showViewModal($id)
  {
    try {
      if (!Gate::allows("view reviews")) {
        throw new UnauthorizedException("can not view review");
      }

      $this->selectedReview = ProductReview::findOrFail($id);

      $this->dispatch("open-review-view-modal");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Review not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function viewReviewOnPage($reviewId)
  {
    try {
      $review = ProductReview::findOrFail($reviewId);
      session()->put("reviewId", $review->id);
      $url = route("products.show", [
        "category_slug" => $review->product->category->slug,
        "product_slug" => $review->product->slug
      ]);
      $fragment = "#review-" . $review->id;
      $url .= $fragment;
      return redirect()->to($url);
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Review not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function showEditModal($id, $referred = false)
  {
    try {
      $review = ProductReview::withTrashed()->findOrFail($id);
      $this->selectedReview = $review;
      $this->editForm->title = $review->title;
      $this->editForm->comment = $review->comment;
      $referred ? $this->dispatch("open-review-edit-modal-referred") : $this->dispatch("open-review-edit-modal");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Review not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function update()
  {
    try {
      if (!Gate::allows("edit reviews")) {
        $this->dispatch("close-review-edit-modal");
        throw new UnauthorizedException("can not edit review");
      }

      $this->editForm->validate();

      $this->selectedReview->update([
        "title" => $this->editForm->title,
        "comment" => $this->editForm->comment,
        "updated_by" => auth()->user()->id,
        "updated_at" => Carbon::now(),
      ]);

      $this->dispatch("close-review-edit-modal");
      $this->dispatch("update_review_success");
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (Throwable $e) {
      dd($e->getMessage());
      $this->dispatch("something-went-wrong");
    }
  }

  public function changeStatus($reviewId, $statusValue)
  {
    try {
      if (!Gate::allows("edit reviews")) {
        throw new UnauthorizedException("can not edit review");
      }
      ProductReview::findOrFail($reviewId)->update([
        "status" => ReviewStatusType::from($statusValue)->value
      ]);
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Review not found!");
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  #[On("delete-review-modal-is-confirmed")]
  public function delete($reviewId)
  {
    try {
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
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Review not found!");
    } catch (Throwable $e) {
      if ($e->getCode() == 401) {
        return $this->dispatch("error-with-message", message: "Can not delete nonrejected reviews.", timer: 1500);
      }
      $this->dispatch("something-went-wrong");
    }
  }

  #[On("force-delete-review-modal-is-confirmed")]
  public function forceDelete($reviewId)
  {
    try {
      if (!Gate::allows("force delete reviews")) {
        throw new UnauthorizedException("you cant delete review permanently");
      }
      ProductReview::withTrashed()->findOrFail($reviewId)->forceDelete();
      $this->dispatch("force-delete-review-success");
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Review not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function restore($reviewId)
  {
    try {
      if (!Gate::allows("force delete reviews")) {
        throw new UnauthorizedException("you cant restore review");
      }
      ProductReview::withTrashed()->findOrFail($reviewId)->restore();
      $this->dispatch("refresh-flowbite");
    } catch (UnauthorizedException $e) {
      // dd($e->getMessage());
      $this->dispatch("unauthorized-action");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Review not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }
  public function render()
  {
    return view('livewire.admin.reviews')->layout("components.admin-layout")->section("content");
  }
}
