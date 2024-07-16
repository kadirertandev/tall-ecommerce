<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\WeeklyDealProduct;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateWeeklyDealProducts extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:update-weekly-deal-products';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Execute the console command.
   */
  public function handle()
  {

    /* $yesterday = Carbon::yesterday();
    $monday = $yesterday->addDays(2);
    $nextSunday = $monday->addDays(6); */

    #test - cuma günü
    $yesterday = Carbon::today()->addDay();
    $monday = Carbon::today()->addDay()->addDays(2);
    $nextSunday = Carbon::today()->addDay()->addDays(2)->addDays(6);
    #test - cuma günü

    WeeklyDealProduct::truncate();

    $createdIDs = [];
    $productIDs = Product::pluck("id", "id")->toArray();
    for ($i = 1; $i <= 3; $i++) {
      $randomId = array_rand($productIDs);
      while (in_array($randomId, $createdIDs)) {
        $randomId = array_rand($productIDs);
      }
      $createdIDs[] = $randomId;
      WeeklyDealProduct::create([
        "product_id" => $randomId,
        "start_date" => $monday,
        "end_date" => $nextSunday,
        "discount_amount" => 700
      ]);
    }
  }
}
