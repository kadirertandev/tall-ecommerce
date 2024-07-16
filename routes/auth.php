<?php

use App\Livewire\Cart;
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

Route::middleware("guest")->group(function () {
  Route::get("/login", function () {
    return view("auth.login");
  })->name("login");

  Route::get("/register", function () {
    return view("auth.register");
  })->name("register");

  Route::get("/forgot-password", function () {
    return view("auth.forgot-password");
  })->name("forgot-password");

  Route::get("/reset-password/{token}", function ($token) {
    return view("auth.reset-password", compact("token"));
  })->name("reset-password");
});

Route::middleware("auth")->group(function () {
  Route::post("/logout", function () {
    auth()->logout();

    session()->invalidate();
    session()->regenerateToken();

    return to_route("home")->with("logout-success", __("frontend.logout-success"));
  })->name("logout");

  Route::prefix("user")->name("auth.user.")->middleware("customer")->group(function () {
    Route::get("/", function () {
      return to_route("auth.user.profile");
    });

    Route::get("/profile", function () {
      return view("auth.user.profile");
    })->name("profile");

    Route::get("/favorites", function () {
      return view("auth.user.favorites");
    })->name("favorites");

    Route::get("/orders", function () {
      return view("auth.user.orders");
    })->name("orders");

    Route::get("/reviews", function () {
      return view("auth.user.reviews");
    })->name("reviews");

    Route::get("/addresses", function () {
      return view("auth.user.addresses");
    })->name("addresses");

    Route::get("/change-password", function () {
      return view("auth.user.change-password");
    })->name("change-password");

    Route::get("/cart", Cart::class)->name("cart");
  });

});