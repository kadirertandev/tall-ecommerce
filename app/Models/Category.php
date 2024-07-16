<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
  use HasFactory, SoftDeletes;

  protected $with = [
    "brands:id,name,slug"
  ];

  protected $fillable = [
    "name",
    "slug",
    "image",
    "is_popular",
    "created_by",
    "updated_by",
    "deleted_by",
  ];

  public function brands()
  {
    return $this->belongsToMany(Brand::class, "category_brands");
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
