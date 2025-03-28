<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\LoginForm;
use App\Livewire\Auth\RegisterForm;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Cart;
use App\Livewire\UserProfileAddresses;
use App\Livewire\UserProfileChangePasswordForm;
use App\Livewire\UserProfileFavorites;
use App\Livewire\UserProfileOrders;
use App\Livewire\UserProfileReviews;
use App\Livewire\UserProfile;
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
  Route::get("/login", LoginForm::class)->name("login");

  Route::get("/register", RegisterForm::class)->name("register");

  Route::get("/forgot-password", ForgotPassword::class)->name("forgot-password");

  Route::get("/reset-password/{token}", ResetPassword::class)->name("reset-password");
});

Route::middleware("auth")->group(function () {
  Route::post("/logout", function () {
    auth()->logout();

    session()->invalidate();
    session()->regenerateToken();

    return to_route("home")->with("logout-success", __("frontend.logout-success"));
  })->name("logout");

  Route::prefix("user")->name("auth.user.")->middleware("customer")->group(function () {
    Route::redirect("/", "/user/profile");

    Route::get("/profile", UserProfile::class)->name("profile");

    Route::get("/favorites", UserProfileFavorites::class)->name("favorites");

    Route::get("/orders", UserProfileOrders::class)->name("orders");

    Route::get("/reviews", UserProfileReviews::class)->name("reviews");

    Route::get("/addresses", UserProfileAddresses::class)->name("addresses");

    Route::get("/change-password", UserProfileChangePasswordForm::class)->name("change-password");

    Route::get("/cart", Cart::class)->name("cart");
  });

});