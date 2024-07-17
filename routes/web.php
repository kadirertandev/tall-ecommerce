<?php


use App\Http\Controllers\ProductController;
use App\Livewire\ProductsByBrand;
use App\Livewire\ProductsByCategory;
use Illuminate\Support\Facades\Route;

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
    return view("home");
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