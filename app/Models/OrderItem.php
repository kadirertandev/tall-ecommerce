<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
  use HasFactory;

  protected $fillable = [
    "order_id",
    "product_id",
    "price",
    "quantity",
    "item_total_price",
    "created_at"
  ];

  public function product()
  {
    return $this->belongsTo(Product::class)->withTrashed();
  }
}
