<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
  use HasFactory;
  protected $fillable = [
    "user_id",
    "city",
    "district",
    "neighborhood",
    "address_line",
    "total_price",
    "status"
  ];

  protected $casts = [
    "status" => \App\Enums\OrderStatusType::class
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public function items()
  {
    return $this->hasMany(OrderItem::class);
  }

  public function products()
  {
    return $this->items->map(function ($item) {
      return $item->product;
    });
  }

  public function subtotal()
  {
    return $this->items->sum('item_total_price');
  }

  public function scopeSearch($query, $value)
  {
    $query->where("city", "like", "%{$value}%")
      ->orWhere("district", "like", "%{$value}%")
      ->orWhere("neighborhood", "like", "%{$value}%")
      ->orWhere("address_line", "like", "%{$value}%")
      ->orWhereHas('user', function ($q) use ($value) {
        $q->where('first_name', 'like', "%{$value}%")
          ->orWhere('last_name', 'like', "%{$value}%")
          ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$value}%");
      });
  }
}
