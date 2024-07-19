<?php

namespace App\Livewire\Admin;

use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
  public $months = 12;
  public function updatedMonths()
  {
    $this->dispatch("months-updated");
  }

  public $totalSales;
  public $totalRevenue;
  public $monthlySales = [];
  public $monthlyRevenue = [];

  public function rendering()
  {
    $this->monthlySales = [];
    $this->monthlyRevenue = [];

    $this->totalSales = OrderItem::sum("quantity");
    $this->totalRevenue = OrderItem::sum("item_total_price");

    $currentDate = Carbon::now();

    for ($i = 0; $i <= $this->months - 1; $i++) {
      $startOfMonth = $currentDate->copy()->subMonths($i)->startOfMonth();
      $endOfMonth = $currentDate->copy()->subMonths($i)->endOfMonth();

      $monthlySalesData = OrderItem::whereBetween("created_at", [$startOfMonth, $endOfMonth])->sum("quantity");
      $monthlyRevenueData = OrderItem::whereBetween("created_at", [$startOfMonth, $endOfMonth])->sum("item_total_price");

      $this->monthlySales[] = [
        "start" => $startOfMonth->toDateString(),
        "end" => $endOfMonth->toDateString(),
        "month" => Carbon::parse($startOfMonth)->monthName,
        "order" => $this->months - 1 - $i,
        "data" => $monthlySalesData
      ];

      $this->monthlyRevenue[] = [
        "start" => $startOfMonth->toDateString(),
        "end" => $endOfMonth->toDateString(),
        "month" => Carbon::parse($startOfMonth)->monthName,
        "order" => $this->months - 1 - $i,
        "data" => $monthlyRevenueData
      ];
    }
  }

  public function render()
  {
    return view('livewire.admin.dashboard')->layout("components.admin-layout")->section("content");
  }
}
