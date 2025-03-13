<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatusType;
use App\Models\Order;
use App\Traits\WithRefreshFlowbite;
use App\Traits\WithTryCatch;
use Illuminate\Support\Facades\DB;
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
    "city" => "City",
    "district" => "District",
    "neighborhood" => "Neighborhood",
    "address_line" => "Address Line",
    "total_price" => "Total Price",
    "status" => "Status",
    "created_at" => "Order Date",
  ];

  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
  }

  #[Computed()]
  public function orders()
  {
    return Order::search($this->keyword)
      ->when($this->sortBy != "user_id", function ($query) {
        $query->when($this->sortBy && $this->sortDir, function ($query) {
          return $query->orderBy($this->sortBy, $this->sortDir);
        });
      })
      ->when($this->sortBy == "user_id", function ($query) {
        $query->join("users", "orders.user_id", "=", "users.id")
          ->select("orders.*", DB::raw("CONCAT(users.first_name, users.last_name) as user_full_name"))
          ->orderBy("user_full_name", $this->sortDir);
      })
      ->when($this->statusFilter, function ($query) {
        $query->whereIn("status", $this->statusFilter);
      })
      ->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  public $selectedOrder;
  public function showViewModal($id)
  {
    $this->tryCatch(function () use ($id) {
      $this->selectedOrder = Order::findOrFail($id);

      $this->dispatch("open-order-view-modal");
    });
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
