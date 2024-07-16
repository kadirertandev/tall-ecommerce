<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class Customers extends Component
{
  use WithPagination;
  public $page = 1;
  public function updatedPage()
  {
    $this->dispatch("refresh-flowbite");
  }
  public $sortDir = "";
  public $sortBy = "";
  public $keyword = "";
  public function updatedKeyword()
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
  public $withDeleteRequest = false;
  public $onlyDeleteRequest = false;
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
  public function updatedWithDeleteRequest()
  {
    $this->withDeleteRequest == true ? $this->onlyDeleteRequest = false : "";
    $this->dispatch("refresh-flowbite");

  }
  public function updatedonlyDeleteRequest()
  {
    $this->onlyDeleteRequest == true ? $this->withDeleteRequest = false : "";
    $this->dispatch("refresh-flowbite");
  }

  #[Computed()]
  public function customersTemplate()
  {
    return User::search($this->keyword)
      ->when($this->sortBy != "full_name", function ($query) {
        $query->when($this->sortBy && $this->sortDir, function ($query) {
          return $query->orderBy($this->sortBy, $this->sortDir);
        });
      })
      ->when($this->sortBy == "full_name", function ($query) {
        $query->when($this->sortBy && $this->sortDir, function ($query) {
          return $query->orderBy(DB::raw("CONCAT(first_name, ' ', last_name)"), $this->sortDir);
        });
      })
      ->when($this->withTrashed == true, function ($query) {
        $query->withTrashed();
      })
      ->when($this->onlyTrashed == true, function ($query) {
        $query->onlyTrashed();
      })
      ->when($this->withDeleteRequest == true, function ($query) {
        $query->where("delete_request", true)->orWhere("delete_request", false);
      })
      ->when($this->onlyDeleteRequest == true, function ($query) {
        $query->where("delete_request", true);
      })
      ->where("is_admin", false);
  }

  #[Computed()]
  public function customers()
  {
    return $this->customersTemplate->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  #[Computed()]
  public function columns()
  {
    return [
      "full_name" => "Customer",
      "email" => "Email",
      "email_verified_at" => "Email Verified At",
      "phone_number" => "Phone Number",
      "created_at" => "Joined At",
    ];
  }

  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
    $this->dispatch("refresh-flowbite");
  }

  public $selectedCustomer;
  public function showViewModal($id)
  {
    try {
      if (!Gate::allows("view customers")) {
        throw new UnauthorizedException("can not view customer");
      }

      $this->selectedCustomer = User::withTrashed()->findOrFail($id);

      $this->dispatch("open-customer-view-modal");
      $this->dispatch("refresh-flowbite");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Customer not found!");
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function delete($customerId)
  {
    // $this->authorize("delete products");
    try {
      if (!Gate::allows("delete customers")) {
        throw new UnauthorizedException("can not delete customer");
      }

      $customer = User::findOrFail($customerId);

      if ($customer->delete_request) {
        $customer->forceDelete();
        $this->dispatch("force_delete_customer_success");
      } else {
        $this->dispatch("something-went-wrong");
      }
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Customer not found!");
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function render()
  {
    return view('livewire.admin.customers')->layout("components.admin-layout")->section("content");
  }
}
