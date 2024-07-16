<?php

use Illuminate\Support\Facades\Route;

Route::name("blog.")->group(function () {
  Route::get("/", function () {
    return "blog home page - " . '<a href="' . route("aboutus") . '" class="text-gray-500">Home</a>';
  })->name("home");
});