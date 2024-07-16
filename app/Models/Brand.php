<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    "name",
    "slug",
    "image",
    "created_by",
    "updated_by",
    "deleted_by",
  ];

  public function productCount($categoryId)
  {
    return Product::where("category_id", $categoryId)->where("brand_id", $this->id)->count();
  }

  public function categories()
  {
    return $this->belongsToMany(Category::class, "category_brands");
  }

  public function scopeSearch($query, $value)
  {
    return $query->where("name", "like", "%{$value}%")
      ->orWhere("slug", "like", "%{$value}%");
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
}
