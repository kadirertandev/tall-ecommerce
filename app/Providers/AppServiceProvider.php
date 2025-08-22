<?php

namespace App\Providers;

use App\Models\Brand;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Category;
use App\Models\DailyDealProduct;
use App\Models\WeeklyDealProduct;
use App\Support\Cache\CategoryCache;
use Illuminate\Support\Facades\App;
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
    if (App::runningInConsole()) {
      return;
    }

    View::share("categories", CategoryCache::get());

    View::composer("home", function ($view) {
      $view->with("popularCategories", Cache::remember("popularCategories", 60 * 60 * 24, function () {
        return Category::where("is_popular", 1)
          ->without("brands")
          ->select(["slug", "image"])->get();
      }));

      $view->with("popularBrands", Cache::remember("popularBrands", 60 * 60 * 24, function () {
        return Brand::where("is_popular", 1)
          ->select(["slug", "image"])->get();
      }));
    });

    View::composer(["home", "livewire.cart"], function ($view) {
      $view->with("weekly_deal_products", Cache::remember("weeklyDealProducts", 60 * 60 * 24, function () {
        return WeeklyDealProduct::with([
          "product" => fn($query) => $query->withReviewRatingAverageAndReviewCount()
            ->with([
              "category" => fn($q) => $q->without("brands"),
              "brand"
            ])
        ])->get();
      }));

      $view->with("daily_deal_products", Cache::remember("dailyDealProducts", 60 * 60 * 24, function () {
        return DailyDealProduct::with([
          "product" => fn($query) => $query->withReviewRatingAverageAndReviewCount()
            ->with([
              "category" => fn($q) => $q->without("brands"),
              "brand"
            ])
        ])->get();
      }));
    });
  }
}
