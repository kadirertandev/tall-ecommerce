<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatusType;
use App\Models\Order;
use App\Traits\WithInteractModal;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithTableSortAndFilter;
use App\Traits\WithTryCatch;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
  use WithPagination;
  use WithTryCatch;
  use WithRefreshFlowbite;
  use WithInteractModal;
  use WithTableSortAndFilter;

  public function boot()
  {
    $this->refreshFlobwite();
  }

  public $statusFilter = [];
  public $columns = [
    "customer_name" => "Customer",
    "city" => "City",
    "district" => "District",
    "neighborhood" => "Neighborhood",
    "address_line" => "Address Line",
    "subtotal" => "Total Price",
    "status" => "Status",
    "created_at" => "Order Date",
  ];

  #[Computed()]
  public function orders()
  {
    return Order::with("user")
      ->search($this->keyword)
      ->withCustomerName()
      ->withSubTotal()
      ->sortByColumn($this->sortBy, $this->sortDir)
      ->filterByStatus($this->statusFilter)
      ->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  public $selectedOrder;

  public function showViewModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->selectedOrder = Order::with([
        "user",
        "items" => fn($q) => $q->with([
          "product" => fn($q) => $q->with([
            "category" => fn($q) => $q->select(["id", "name", "slug"])->without("brands"),
            "brand" => fn($q) => $q->select(["id", "name", "slug"])
          ])->select(["id", "name", "slug", "image", "category_id", "brand_id"])
        ])
      ])->findOrFail($id);

      $this->showModal("view-order");
    });
  }

  public function afterModalClosed($modalName)
  {
    $this->reset("selectedOrder");
  }

  public function changeStatus($orderId, $statusValue)
  {
    $this->tryCatch(function () use ($orderId, $statusValue) {
      if (!Gate::allows("edit orders")) {
        throw new UnauthorizedException("can not edit order");
      }

      Order::findOrFail($orderId)->update([
        "status" => OrderStatusType::from($statusValue)->value
      ]);
    });
  }

  public function render()
  {
    return view('livewire.admin.orders')
      ->layout("components.admin-layout", ["title" => "Orders"])
      ->section("content");
  }
}
