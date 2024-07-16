<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UpdatePopularBrandsAndCategories extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:update-popular-brands-and-categories';

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
    $monday = Carbon::now()->startOfWeek()/* ->addSecond()->addMillisecond() */ ;
    $sunday = Carbon::now()->endOfWeek();
    $sevenDaysAgo = Carbon::now()->subDays(7);

    $productsSoldLast7Days = DB::table('order_items')
      ->join('orders', 'order_items.order_id', '=', 'orders.id')
      ->join('products', 'order_items.product_id', '=', 'products.id')
      ->where('orders.created_at', '>=', $sevenDaysAgo)
      ->select('products.id', 'products.category_id', 'products.brand_id', DB::raw('SUM(order_items.quantity) as total_quantity_sold'))
      ->groupBy('products.id')
      ->orderBy("total_quantity_sold", "desc")
      ->take(6)
      ->get();

    $popular_categories = $productsSoldLast7Days->pluck('category_id')->unique()->toArray();
    $non_popular_categories = Category::whereNotIn("id", $popular_categories)->pluck("id", "id")->toArray();

    $popular_brands = $productsSoldLast7Days->pluck("brand_id")->unique()->toArray();
    $non_popular_brands = Brand::whereNotIn("id", $popular_brands)->pluck("id", "id")->toArray();

    if (count($popular_categories) < 6) {
      $createdCategoryIDs = [];
      for ($i = 1; $i <= 6 - count($popular_categories); $i++) {
        $randomID = array_rand($non_popular_categories);
        while (in_array($randomID, $createdCategoryIDs)) {
          $randomID = array_rand($non_popular_categories);
        }
        $createdCategoryIDs[] = $randomID;
      }
      array_push($popular_categories, ...$createdCategoryIDs);
    }

    if (count($popular_brands) < 6) {
      $createdBrandIDs = [];
      for ($i = 1; $i <= 6 - count($popular_brands); $i++) {
        $randomID = array_rand($non_popular_brands);
        while (in_array($randomID, $createdBrandIDs)) {
          $randomID = array_rand($non_popular_brands);
        }
        $createdBrandIDs[] = $randomID;
      }
      array_push($popular_brands, ...$createdBrandIDs);
    }

    Category::query()->update(["is_popular" => false]);

    Category::whereIn("id", $popular_categories)->update(
      [
        "is_popular" => true
      ]
    );

    Brand::query()->update(["is_popular" => false]);

    Brand::whereIn("id", $popular_brands)->update(
      [
        "is_popular" => true
      ]
    );
  }
}
