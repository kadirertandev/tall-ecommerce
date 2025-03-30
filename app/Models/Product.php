<?php

namespace App\Models;

use App\Enums\ReviewStatusType;
use App\Traits\ScopeFilterByTrashed;
use App\Traits\ScopeWithoutColumns;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Product extends Model
{
  use HasFactory, SoftDeletes, ScopeWithoutColumns, ScopeFilterByTrashed;

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

  public function title()
  {
    $brand = $this->brand;
    $name = $this->name;
    $description = $this->description;

    if (Str::startsWith($name, $brand->name)) {
      $name = Str::remove($brand->name, $name);
    }

    return '<a href="' . route('brand-slug', ['slug' => $brand->slug]) . '" class="font-bold">' . $brand->name . '</a> ' .
      '<span class="font-thin">' . $name . ' ' . $description . '</span>';
  }

  public function finalPrice()
  {
    return $this->price - $this->discount_amount;
  }

  public function reviews()
  {
    return $this->hasMany(ProductReview::class)->where("status", "approved");
  }

  public function brand()
  {
    return $this->belongsTo(Brand::class);
  }

  public function category()
  {
    return $this->belongsTo(Category::class, "category_id");
  }

  public function ratingAverage()
  {
    return $this->reviews()->avg("rating") ?? 0;
  }

  public function scopeSearch($query, $value)
  {
    $query->where("name", "like", "%{$value}%")
      ->orWhere("description", "like", "%{$value}%")

      ->orWhereHas('category', function ($q) use ($value) {
        $q->where("name", "like", "%{$value}%");
      })

      ->orWhereHas('brand', function ($q) use ($value) {
        $q->where("name", "like", "%{$value}%");
      });
  }

  public function scopeWithSubQueryFields($query)
  {
    return $query->addSelect([
      "category_name" => Category::select("name")->whereColumn("id", "products.category_id"),
      "brand_name" => Brand::select("name")->whereColumn("id", "products.brand_id"),
      "total_sales" => OrderItem::select(DB::raw("sum(quantity)"))->whereColumn("product_id", "products.id"),
      "total_revenue" => OrderItem::select(DB::raw("sum(order_items.price * quantity)"))->whereColumn("product_id", "products.id"),
      "review_rating" => ProductReview::select(DB::raw("avg(rating)"))->whereColumn("product_id", "products.id")->where("status", ReviewStatusType::from("approved"))
    ]);
  }

  public function scopeWithReviewRatingAndReviewCount($query)
  {
    return $query->addSelect([
      "review_rating" => ProductReview::select(DB::raw("avg(rating)"))->whereColumn("product_id", "products.id")->where("status", ReviewStatusType::from("approved")),

      "review_count" => ProductReview::select(DB::raw("count(id)"))
        ->whereColumn("product_id", "products.id")
        ->where("status", ReviewStatusType::from("approved"))
    ]);
  }

  public function scopeFilterByCategory($query, $categoriesFilter)
  {
    return $query
      ->when($categoriesFilter, fn($q) => $q->whereIn("category_id", $categoriesFilter));
  }

  public function scopeFilterByBrand($query, $brandsFilter)
  {
    return $query
      ->when($brandsFilter, fn($q) => $q->whereIn("brand_id", $brandsFilter));
  }

  public function scopeFilterByPrice($query, $minPrice, $maxPrice)
  {
    return $query
      ->when($minPrice, fn($q) => $q->where("price", ">=", $minPrice))
      ->when($maxPrice, fn($q) => $q->where("price", "<=", $maxPrice));
  }

  public function scopeSortByColumn($query, $sortBy, $sortDir)
  {
    return $query
      ->when($sortBy && $sortDir, fn($q) => $q->orderBy($sortBy, $sortDir));
  }

  public function createdBy()
  {
    return $this->hasOne(User::class, "id", "created_by");
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
