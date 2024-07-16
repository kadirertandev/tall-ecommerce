<?php

use App\Models\Permission;
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

Route::group(["middleware" => ["auth", "admin"]], function () {
  Route::get("/roles-permissions-test", function () {
    return view("roles-permissions-test", [
      "total_permissions_count" => Permission::all()->count()
    ]);
  })->name("roles-permissions");

  Route::get("/view-products", function () {
    return "view products page";
  })->middleware("can:view products")->name("view-products");
  Route::get("/create-products", function () {
    return "create products page";
  })->middleware("can:create products")->name("create-products");
  Route::get("/edit-products", function () {
    return "edit products page";
  })->middleware("can:edit products")->name("edit-products");
  Route::get("/delete-products", function () {
    return "delete products page";
  })->middleware("can:delete products")->name("delete-products");

  Route::get("/view-categories", function () {
    return "view categories page";
  })->middleware("can:view categories")->name("view-categories");
  Route::get("/create-categories", function () {
    return "create categories page";
  })->middleware("can:create categories")->name("create-categories");
  Route::get("/edit-categories", function () {
    return "edit categories page";
  })->middleware("can:edit categories")->name("edit-categories");
  Route::get("/delete-categories", function () {
    return "delete categories page";
  })->middleware("can:delete categories")->name("delete-categories");

  Route::get("/view-brands", function () {
    return "view brands page";
  })->middleware("can:view brands")->name("view-brands");
  Route::get("/create-brands", function () {
    return "create brands page";
  })->middleware("can:create brands")->name("create-brands");
  Route::get("/edit-brands", function () {
    return "edit brands page";
  })->middleware("can:edit brands")->name("edit-brands");
  Route::get("/delete-brands", function () {
    return "delete brands page";
  })->middleware("can:delete brands")->name("delete-brands");

  Route::get("/view-reviews", function () {
    return "view reviews page";
  })->middleware("can:view reviews")->name("view-reviews");
  Route::get("/create-reviews", function () {
    return "create reviews page";
  })->middleware("can:create reviews")->name("create-reviews");
  Route::get("/edit-reviews", function () {
    return "edit reviews page";
  })->middleware("can:edit reviews")->name("edit-reviews");
  Route::get("/delete-reviews", function () {
    return "delete reviews page";
  })->middleware("can:delete reviews")->name("delete-reviews");

  Route::get("/view-admins", function () {
    return "view admins page";
  })->middleware("can:view admins")->name("view-admins");
  Route::get("/create-admins", function () {
    return "create admins page";
  })->middleware("can:create admins")->name("create-admins");
  Route::get("/edit-admins", function () {
    return "edit admins page";
  })->middleware("can:edit admins")->name("edit-admins");
  Route::get("/delete-admins", function () {
    return "delete admins page";
  })->middleware("can:delete admins")->name("delete-admins");
});