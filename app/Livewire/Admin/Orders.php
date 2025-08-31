<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatusType;
use App\Models\Order;
use App\Traits\WithInteractModal;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithTableSortAndFilter;
use App\Traits\WithTryCatch;
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

  public function mount()
  {
    $this->validateOrderByInputs();
  }

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
  public function allowedColumns()
  {
    return array_keys($this->columns);
  }

  #[Computed()]
  public function orders()
  {
    return Order::with("user")
      ->search($this->keyword)
      ->withCustomerName()
      ->withSubTotal()
      ->sortByColumn($this->orderByColumn, $this->orderByDirection)
      ->filterByStatus($this->statusFilter)
      ->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  public $selectedOrder;

  public function showViewModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->selectedOrder = Order::with([
        "user",
        "orderItems" => fn($q) => $q->with([
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
      $this->authorize("edit orders");

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
