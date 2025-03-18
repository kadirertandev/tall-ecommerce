<?php

namespace Database\Seeders;

use App\Models\DailyDealProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DailyDealProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    DailyDealProduct::create([
      "product_id" => 1,
      // "start_date" => date("Y-m-d H:i:s"),
      "start_date" => Carbon::today()->startOfDay(),
      "end_date" => Carbon::today()->addDay()->startOfDay(),
      "discount_amount" => 1200
    ]);
    DailyDealProduct::create([
      "product_id" => 2,
      "start_date" => Carbon::today()->startOfDay(),
      "end_date" => Carbon::today()->addDay()->startOfDay(),
      "discount_amount" => 500
    ]);
    DailyDealProduct::create([
      "product_id" => 3,
      "start_date" => Carbon::today()->startOfDay(),
      "end_date" => Carbon::today()->addDay()->startOfDay(),
      "discount_amount" => 700
    ]);
  }
}
