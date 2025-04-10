<?php

namespace App\Models;

use App\Traits\ScopeFilterByTrashed;
use App\Traits\ScopeWithoutColumns;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
  use HasFactory, SoftDeletes, ScopeFilterByTrashed, ScopeWithoutColumns;

  protected $fillable = [
    "name",
    "slug",
    "image",
    "created_by",
    "updated_by",
    "updated_at",
    "deleted_by",
  ];

  public function categories()
  {
    return $this->belongsToMany(Category::class, "category_brands");
  }

  public function scopeSearch($query, $value)
  {
    return $query->where("name", "like", "%{$value}%");
  }

  public function scopeSortByColumn($query, $sortBy, $sortDir)
  {
    return $query
      ->when(
        $sortBy && $sortDir,
        function ($query) use ($sortBy, $sortDir) {
          return $query->orderBy($sortBy, $sortDir);
        }
      );
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
