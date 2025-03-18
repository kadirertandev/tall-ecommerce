<?php

namespace Database\Seeders;

use App\Models\WeeklyDealProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class WeeklyDealProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    WeeklyDealProduct::create([
      "product_id" => 4,
      "start_date" => Carbon::today()->subDays(1)->startOfDay(),
      "end_date" => Carbon::today()->subDays(1)->addWeek()->startOfDay(),
      "discount_amount" => 700
    ]);
    WeeklyDealProduct::create([
      "product_id" => 5,
      "start_date" => Carbon::today()->subDays(1)->startOfDay(),
      "end_date" => Carbon::today()->subDays(1)->addWeek()->startOfDay(),
      "discount_amount" => 700
    ]);
    WeeklyDealProduct::create([
      "product_id" => 6,
      "start_date" => Carbon::today()->subDays(1)->startOfDay(),
      "end_date" => Carbon::today()->subDays(1)->addWeek()->startOfDay(),
      "discount_amount" => 700
    ]);
  }
}
