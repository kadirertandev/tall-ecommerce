<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatusType;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\UnauthorizedException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class Orders extends Component
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

  #[Computed()]
  public function ordersTemplate()
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
      });
  }

  #[Computed()]
  public function orders()
  {
    return $this->ordersTemplate->paginate(($this->perPage >= 5) ? $this->perPage : 5);
  }

  #[Computed()]
  public function columns()
  {
    return [
      "user_id" => "Customer",
      "city" => "City",
      "district" => "District",
      "neighborhood" => "Neighborhood",
      "address_line" => "Address Line",
      "total_price" => "Total Price",
      "status" => "Status",
      "created_at" => "Order Date",
    ];
  }

  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
    $this->dispatch("refresh-flowbite");
  }

  public $selectedOrder;
  public function showViewModal($id)
  {
    try {
      if (!Gate::allows("view orders")) {
        throw new UnauthorizedException("can not view order");
      }

      $this->selectedOrder = Order::findOrFail($id);

      $this->dispatch("open-order-view-modal");
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Order not found!");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function changeStatus($orderId, $statusValue)
  {
    try {
      if (!Gate::allows("edit orders")) {
        throw new UnauthorizedException("can not edit order");
      }

      Order::findOrFail($orderId)->update([
        "status" => OrderStatusType::from($statusValue)->value
      ]);
    } catch (ModelNotFoundException $e) {
      $this->dispatch("error-with-message", message: "Order not found!");
    } catch (UnauthorizedException $e) {
      $this->dispatch("unauthorized-action");
    } catch (Throwable $e) {
      $this->dispatch("something-went-wrong");
    }
  }

  public function render()
  {
    return view('livewire.admin.orders')->layout("components.admin-layout")->section("content");
  }
}
