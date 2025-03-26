<?php


use App\Http\Middleware\LastViewedProducts;
use App\Livewire\ProductsByBrand;
use App\Livewire\ProductsByCategory;
use App\Livewire\ProductShow;
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
  Route::view("/", "home")->name("home");

  Route::view("/aboutus", "static.aboutus")->name("aboutus");

  Route::view("/help-support", "static.help-support")->name("help-support");

  Route::view("/contact", "static.contact")->name("contact");
});

Route::get("/category/{slug}", ProductsByCategory::class)->name("category-slug");
Route::get("/brand/{slug}", ProductsByBrand::class)->name("brand-slug");
Route::get("/category/{category_slug}/{product_slug}", ProductShow::class)->name("products.show")->middleware(LastViewedProducts::class);