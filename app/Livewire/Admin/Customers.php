<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Traits\WithInteractModal;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithSweetAlert;
use App\Traits\WithTableSortAndFilter;
use App\Traits\WithTryCatch;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Customers extends Component
{
  use WithPagination;
  use WithTryCatch;
  use WithRefreshFlowbite;
  use WithSweetAlert;
  use WithInteractModal;
  use WithTableSortAndFilter;

  public function boot()
  {
    $this->refreshFlobwite();
  }

  public $columns = [
    "full_name" => "Customer",
    "email" => "Email",
    "email_verified_at" => "Email Verified At",
    "phone_number" => "Phone Number",
    "created_at" => "Joined At",
    "delete_request" => "Delete Request",
  ];

  #[Url()]
  public $onlyDeleteRequest = false;
  public function updatedonlyDeleteRequest()
  {
    if ($this->onlyDeleteRequest == true)
      $this->withDeleteRequest = false;
  }

  #[Computed()]
  public function customers()
  {
    return User::where("is_admin", false)
      ->search($this->keyword)
      ->withoutColumns(["is_admin", "password", "deleted_by", "remember_token", "updated_at"])
      ->sortByColumn($this->sortBy, $this->sortDir)
      ->filterByDeleteRequest($this->onlyDeleteRequest)
      ->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  public $selectedCustomer;
  public function showViewModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->selectedCustomer = User::findOrFail($id);

      $this->showModal("view-customer");
    });
  }

  public function delete($customerId)
  {
    $this->tryCatch(
      function () use ($customerId) {
        if (!Gate::allows("delete customers")) {
          throw new UnauthorizedException("can not delete customer");
        }

        $customer = User::findOrFail($customerId);

        if ($customer->delete_request) {
          $customer->forceDelete();

          $this->swalToast([
            "titleText" => "Customer deleted permanently successfully!"
          ]);
        } else {
          $this->swalTemplateSomethingWentWrong();
        }
      }
    );
  }

  public function render()
  {
    return view('livewire.admin.customers')
      ->layout("components.admin-layout", ["title" => "Customers"])
      ->section("content");
  }
}
