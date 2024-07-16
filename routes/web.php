<?php


use App\Http\Controllers\ProductController;
use App\Livewire\ProductsByBrand;
use App\Livewire\ProductsByCategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\DailyDealProduct;
use App\Models\WeeklyDealProduct;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix("/")->group(function () {
  Route::get('/', function () {
    /* return Cache::has('weeklyDealProducts');
    return Session::all();
    $last_viewed_products = [
      1 => ["id" => 1, "count" => 1],
      2 => ["id" => 2, "count" => 3],
      3 => ["id" => 3, "count" => 5],
    ];
    $product_exists = key_exists(1, $last_viewed_products);
    if ($product_exists) {
      $last_viewed_products[3]["count"] += 1;
    }
    return $last_viewed_products; */

    // file_put_contents(storage_path("logs/laravel.log"), "");


    return view("home");
    /* return view('home', [
      "weekly_deal_products" => $weekly_deal_products,
      "daily_deal_products" => $daily_deal_products,
      "popularCategories" => $popularCategories,
      "popularBrands" => $popularBrands,
    ]); */
  })->name("home");

  Route::get('/aboutus', function () {
    return view('static.aboutus');
  })->name("aboutus");

  Route::get('/help-support', function () {
    return view('static.help-support');
  })->name("help-support");

  Route::get('/contact', function () {
    return view('static.contact');
  })->name("contact");

  Route::get("/deals-of-the-week", function () {
    return view("deals-of-the-week");
  })->name("deals-of-the-week");
});



Route::get("/category/{slug}", ProductsByCategory::class)->name("category-slug");
Route::get("/brand/{slug}", ProductsByBrand::class)->name("brand-slug");
Route::get("/category/{category_slug}/{product_slug}", [ProductController::class, "show"])->name("products.show");

Route::post("/lang", function () {
  session()->put("locale", request("lang"));
  return redirect()->back();
})->name("lang");