<?php

namespace App\Services;

class UserProductFavoriteService
{
  public function add($productId)
  {
    if (!$this->exists($productId)) {
      auth()->user()->favorites()->attach($productId, ['created_at' => now()]);
    }
  }

  public function remove($productId)
  {
    auth()->user()->favorites()->detach($productId);
  }

  private function exists($productId)
  {
    return auth()->user()->favorites()->where("product_id", $productId)->exists();
  }
}