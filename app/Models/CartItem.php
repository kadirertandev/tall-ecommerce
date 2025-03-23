<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
  use HasFactory;

  protected $fillable = ["cart_id", "product_id", "quantity"];

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function totalPriceWithoutDiscount()
  {
    return $this->product->price * $this->quantity;
  }

  public function totalPrice()
  {
    return $this->product->finalPrice() * $this->quantity;
  }
}
