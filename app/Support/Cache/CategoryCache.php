<?php

namespace App\Support\Cache;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryCache
{
  public static function refresh()
  {
    $categories = Category::without('brands')
      ->select(['id', 'slug', 'name'])
      ->get();

    Cache::put('categories', $categories, 60 * 5);

    return $categories;
  }

  public static function get()
  {
    return Cache::remember('categories', 60 * 5, function () {
      return Category::without('brands')
        ->select(['id', 'slug', 'name'])
        ->get();
    });
  }
}