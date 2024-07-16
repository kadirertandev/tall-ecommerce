<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Product extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    "name",
    "slug",
    "description",
    "price",
    "discount_amount",
    "image",
    "category_id",
    "brand_id",
    "created_by",
    "created_at",
    "updated_by",
    "updated_at",
    "deleted_by",
  ];

  protected $with = [
    "category:id,name,slug",
    "brand:id,name,slug",
  ];

  public function brand()
  {
    return $this->belongsTo(Brand::class);
  }

  public function title()
  {
    $brand = $this->brand->name;
    $name = $this->name;
    $description = $this->description;
    if (Str::startsWith($name, $brand)) {
      $name = Str::remove($brand, $name);
    }
    return '<a href="' . route('brand-slug', ['slug' => $this->brand->slug]) . '" class="font-bold">' . $brand . '</a> ' .
      '<span class="font-thin">' . $name . ' ' . $description . '</span>';
  }

  public function reviews()
  {
    return $this->hasMany(ProductReview::class)->where("status", "approved");
  }

  public function ratingAverage()
  {
    // $ratingAverage = ProductReview::where("product_id", $this->id)->pluck("rating")->average();
    $ratingAverage = $this->reviews()->pluck("rating")->average();

    return $ratingAverage ?? 0;
  }

  public function scopeSearch($query, $value)
  {
    $query->where("products.name", "like", "%{$value}%")
      ->orWhere("products.title", "like", "%{$value}%")
      ->orWhere("products.description", "like", "%{$value}%");
  }

  public function category()
  {
    return $this->belongsTo(Category::class, "category_id");
  }

  public function totalSale()
  {
    /* $count = 0;
    foreach (Order::all() as $order) {
      foreach ($order->items as $item) {
        if ($this->id == $item->product->id) {
          $count += $item->quantity;
        }
      }
    }
    return $count; */

    $totalSold = DB::table('order_items')
      ->select(DB::raw('sum(quantity) as total_sold'))
      ->where('product_id', $this->id)
      ->groupBy('product_id')
      ->value('total_sold');

    return $totalSold ?: 0;

    /* return $this->hasMany(OrderItem::class)
      ->select(DB::raw('sum(quantity) as total_sold'))
      ->groupBy('product_id')
      ->first()
      ->total_sold ?? 0; // Handle cases where no orders exist */
  }

  public function revenue()
  {
    $revenue = 0;
    foreach (Order::all() as $order) {
      foreach ($order->items as $item) {
        if ($this->id == $item->product->id) {
          $revenue += $item->item_total_price;
        }
      }
    }
    return $revenue;
  }

  public function updatedBy()
  {
    return $this->hasOne(User::class, "id", "updated_by");
  }
  public function deletedBy()
  {
    return $this->hasOne(User::class, "id", "deleted_by");
  }

  /**
   * Get the formatted price for the product. in blade, instead of App\Helpers::formatPrice($product->price) we can now use $product->price
   *
   * @param  float  $value
   * @return string
   */
  /* public function getPriceAttribute($value)
  {
    return number_format($value, 2, ',', '.');
  } */
}
