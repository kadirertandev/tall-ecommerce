<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
  use HasFactory;

  protected $fillable = ["user_id"];

  public function items()
  {
    return $this->hasMany(CartItem::class);
  }

  public function products()
  {
    return $this->items->map(function ($record) {
      return $record->product;
    });
  }

  public function subtotal()
  {
    return $this->items->sum('item_total_price');
    $subtotal = 0;
    $items = $this->items;
    $totalPrices = function () use ($items) {
      foreach ($items as $item) {
        yield $item->item_total_price;
      }
    };
    foreach ($totalPrices() as $price) {
      $subtotal += $price;
    }

    return $subtotal;
  }
}
