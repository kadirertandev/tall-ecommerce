<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cart extends Model
{
  use HasFactory;

  protected $fillable = ["user_id"];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

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
    return DB::table('cart_items')
      ->join('products', 'cart_items.product_id', '=', 'products.id')
      ->where('cart_items.cart_id', $this->id)
      ->selectRaw('SUM((products.price - products.discount_amount) * cart_items.quantity) as subtotal')
      ->value('subtotal');
  }
}
