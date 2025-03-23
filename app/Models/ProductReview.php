<?php

namespace App\Models;

use App\Traits\ScopeFilterByStatus;
use App\Traits\ScopeFilterByTrashed;
use App\Traits\ScopeWithoutColumns;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ProductReview extends Model
{
  use HasFactory, SoftDeletes, ScopeFilterByStatus, ScopeFilterByTrashed, ScopeWithoutColumns;

  protected $fillable = [
    "title",
    "comment",
    "rating",
    "user_id",
    "product_id",
    "status",
    "updated_by",
    "deleted_by",
  ];

  protected $casts = [
    "status" => \App\Enums\ReviewStatusType::class
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  public function updatedBy()
  {
    return $this->hasOne(User::class, "id", "updated_by");
  }

  public function deletedBy()
  {
    return $this->hasOne(User::class, "id", "deleted_by");
  }

  public function scopeSearch($query, $value)
  {
    $query->where("title", "like", "%{$value}%")
      ->orWhere("comment", "like", "%{$value}%")
      ->orWhereHas('user', function ($q) use ($value) {
        $q->where('first_name', 'like', "%{$value}%")
          ->orWhere('last_name', 'like', "%{$value}%")
          ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$value}%");
      })
      ->orWhereHas('product', function ($q) use ($value) {
        $q->where('name', 'like', "%{$value}%")
          ->orWhere('description', 'like', "%{$value}%")
          ->orWhere('title', 'like', "%{$value}%");
      });
  }

  public function scopeSortByColumn($query, $sortBy, $sortDir)
  {
    return $query
      ->when($sortBy && $sortDir, fn($q) => $q->orderBy($sortBy, $sortDir));
  }

  public function scopeWithSubQueryFields($query)
  {
    return $query->addSelect([
      "customer_name" => User::select(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"))->whereColumn("id", "product_reviews.user_id"),
      "product_name" => Product::select("name")->whereColumn("id", "product_reviews.product_id")
    ]);
  }
}
