<?php

namespace App\Livewire\Admin;

use App\Enums\OrderStatusType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
  public $i = 6;
  public $filter = "last_6_months";
  public function updatedFilter()
  {
    $this->dispatch("filter-updated");
  }
  public $salesData = [];
  public $revenueData = [];

  public $totalSales;
  public $totalRevenue;
  public $totalOrders;
  public $totalProducts;
  public $totalCustomers;

  public function mount()
  {
    $this->totalSales = OrderItem::sum("quantity");
    $this->totalRevenue = OrderItem::sum("item_total_price");
    $this->totalOrders = Order::where("status", OrderStatusType::ORDER_PLACED)->count();
    $this->totalProducts = Product::count();
    $this->totalCustomers = User::where("is_admin", false)->count();
  }

  public function rendering()
  {
    $this->salesData = [];
    $this->revenueData = [];

    $currentDate = Carbon::now();

    switch ($this->filter) {
      case 'yesterday':
      case 'today':
        $isYesterday = $this->filter === "yesterday" ? true : false;
        $this->salesData["header_label"] = "Sale";
        $this->revenueData["header_label"] = "Revenue";

        $startOfDay = $currentDate->copy()->{$isYesterday ? 'yesterday' : 'today'}()->startOfDay();
        $endOfDay = $currentDate->copy()->{$isYesterday ? 'yesterday' : 'today'}()->endOfDay();

        $salesData = OrderItem::whereBetween("created_at", [$startOfDay, $endOfDay])->sum("quantity");
        $revenueData = OrderItem::whereBetween("created_at", [$startOfDay, $endOfDay])->sum("item_total_price");

        $this->salesData['data'][] = [
          "label" => Carbon::parse($startOfDay)->toDateString(),
          "order" => 1,
          "data" => $salesData
        ];

        $this->revenueData['data'][] = [
          "label" => Carbon::parse($startOfDay)->toDateString(),
          "order" => 1,
          "data" => $revenueData
        ];
        break;
      case 'last_7_days':
      case 'last_30_days':
      case 'last_90_days':
        $this->i = $this->filter === "last_7_days" ? 7
          : ($this->filter === "last_30_days" ? 30
            : 90
          );

        $this->salesData["header_label"] = "Sale";
        $this->revenueData["header_label"] = "Revenue";

        for ($i = 0; $i <= $this->i - 1; $i++) {
          $startOfDay = $currentDate->copy()->subDays($i)->startOfDay();
          $endOfDay = $currentDate->copy()->subDays($i)->endOfDay();

          $salesData = OrderItem::whereBetween("created_at", [$startOfDay, $endOfDay])->sum("quantity");
          $revenueData = OrderItem::whereBetween("created_at", [$startOfDay, $endOfDay])->sum("item_total_price");

          $this->salesData['data'][] = [
            "label" => Carbon::parse($startOfDay)->toDateString(),
            "order" => $this->i - 1 - $i,
            "data" => $salesData
          ];

          $this->revenueData['data'][] = [
            "label" => Carbon::parse($startOfDay)->toDateString(),
            "order" => $this->i - 1 - $i,
            "data" => $revenueData
          ];
        }
        break;
      case 'last_6_months':
      case 'last_12_months':
        $this->i = $this->filter === "last_6_months" ? 6 : 12;

        $this->salesData["header_label"] = "Monthly Sale";
        $this->revenueData["header_label"] = "Monthly Revenue";

        for ($i = 0; $i <= $this->i - 1; $i++) {
          $startOfMonth = $currentDate->copy()->subMonths($i)->startOfMonth();
          $endOfMonth = $currentDate->copy()->subMonths($i)->endOfMonth();

          $monthlySalesData = OrderItem::whereBetween("created_at", [$startOfMonth, $endOfMonth])->sum("quantity");
          $monthlyRevenueData = OrderItem::whereBetween("created_at", [$startOfMonth, $endOfMonth])->sum("item_total_price");

          $this->salesData['data'][] = [
            "label" => Carbon::parse($startOfMonth)->monthName,
            "order" => $this->i - 1 - $i,
            "data" => $monthlySalesData
          ];

          $this->revenueData['data'][] = [
            "label" => Carbon::parse($startOfMonth)->monthName,
            "order" => $this->i - 1 - $i,
            "data" => $monthlyRevenueData
          ];
        }
        break;
      default:
    }

    // dd($this->salesData, $this->revenueData);
  }

  public function render()
  {
    return view('livewire.admin.dashboard')->layout("components.admin-layout")->section("content");
  }
}
