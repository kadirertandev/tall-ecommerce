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
    return $this->items()->sum(DB::raw('price * quantity'));
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

  public function scopeSortByColumn($query, $sortBy, $sortDir)
  {
    return $query
      ->when($sortBy && $sortDir, fn($q) => $q->orderBy($sortBy, $sortDir));
  }

  public function scopeFilterByStatus($query, $statusFilter)
  {
    return $query
      ->when($statusFilter, fn($q) => $q->whereIn("status", $statusFilter));
  }

  public function scopeWithCustomerName($query)
  {
    return $query->addSelect([
      "customer_name" => User::select(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"))->whereColumn("users.id", "user_id")
    ]);
  }

  public function scopeWithSubTotal($query)
  {
    return $query->addSelect([
      "subtotal" => OrderItem::select(DB::raw("SUM(price * quantity)"))->whereColumn("order_id", "orders.id")
    ]);
  }
}
