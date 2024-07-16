<?php

use App\Livewire\Admin\Admins;
use App\Livewire\Admin\Brands;
use App\Livewire\Admin\Categories;
use App\Livewire\Admin\Customers;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Orders;
use App\Livewire\Admin\Products;
use App\Livewire\Admin\Reviews;
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

Route::prefix("admin")->name("admin.")->middleware("admin")->group(function () {
  Route::redirect('/', '/admin/dashboard');
  Route::get("/dashboard", Dashboard::class)->name("dashboard");

  Route::prefix("/admins")->name("admins.")->group(function () {
    Route::get("/", Admins::class)->name("index")->middleware("can:view admins");
  });

  /* Route::prefix("/roles")->name("roles.")->group(function () {
    Route::get("/", Roles::class)->name("index")->middleware("can:view roles");
  });

  Route::prefix("/permissions")->name("permissions.")->group(function () {
    Route::get("/", Permissions::class)->name("index")->middleware("can:view permissions");
  }); */

  Route::prefix("/products")->name("products.")->group(function () {
    Route::get("/", Products::class)->name("index")->middleware("can:view products");
  });

  Route::prefix("/categories")->name("categories.")->group(function () {
    Route::get("/", Categories::class)->name("index")->middleware("can:view categories");
  });

  Route::prefix("/brands")->name("brands.")->group(function () {
    Route::get("/", Brands::class)->name("index")->middleware("can:view brands");
  });

  Route::prefix("/customers")->name("customers.")->group(function () {
    Route::get("/", Customers::class)->name("index")->middleware("can:view customers");
  });

  Route::prefix("/orders")->name("orders.")->group(function () {
    Route::get("/", Orders::class)->name("index")->middleware("can:view orders");
  });

  Route::prefix("/reviews")->name("reviews.")->group(function () {
    Route::get("/", Reviews::class)->name("index")->middleware("can:view reviews");
  });
});