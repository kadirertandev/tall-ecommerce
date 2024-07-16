<?php

namespace App\Providers;

use App\Models\Brand;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Category;
use App\Models\DailyDealProduct;
use App\Models\WeeklyDealProduct;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    $categories = Cache::rememberForever("categories", function () {
      return Category::all();
    });
    $weekly_deal_products = Cache::remember("weeklyDealProducts", 60 * 60 * 24, function () {
      return WeeklyDealProduct::all();
    });
    $daily_deal_products = Cache::remember("dailyDealProducts", 60 * 60 * 24, function () {
      return DailyDealProduct::all();
    });
    $popularCategories = Cache::remember("popularCategories", 60 * 60 * 24, function () {
      return Category::where("is_popular", 1)->get();
    });
    $popularBrands = Cache::remember("popularBrands", 60 * 60 * 24, function () {
      return Brand::where("is_popular", 1)->get();
    });

    View::share("categories", $categories);
    View::share("weekly_deal_products", $weekly_deal_products);
    View::share("daily_deal_products", $daily_deal_products);
    View::share("popularCategories", $popularCategories);
    View::share("popularBrands", $popularBrands);
  }
}
