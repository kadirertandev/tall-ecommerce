<?php

namespace App\Console\Commands;

use App\Models\DailyDealProduct;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateDailyDealProducts extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:update-daily-deal-products';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Update daily deal products everyday at 00:00.';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    // $yesterday = Carbon::yesterday();
    $yesterday = Carbon::today(); //for test purposes

    // DailyDealProduct::truncate();
    DailyDealProduct::where("start_date", $yesterday)->delete();

    $createdIDs = [];
    $productIDs = Product::pluck("id", "id")->toArray();
    for ($i = 1; $i <= 3; $i++) {
      $randomId = array_rand($productIDs);
      while (in_array($randomId, $createdIDs)) {
        $randomId = array_rand($productIDs);
      }
      $createdIDs[] = $randomId;
      DailyDealProduct::create([
        "product_id" => $randomId,
        // "start_date" => Carbon::today()->startOfDay(),
        // "end_date" => Carbon::today()->addDay()->startOfDay(),
        "start_date" => Carbon::today()->addDay()->startOfDay(), // for test purpose
        "end_date" => Carbon::today()->addDays(2)->startOfDay(), // for test purpose
        "discount_amount" => 700
      ]);
    }
  }
}
