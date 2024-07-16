<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProductFavorite extends Model
{
  use HasFactory;
  protected $table = 'user_product_favorites';

  public function product()
  {
    return $this->belongsTo(Product::class, 'product_id');
  }
}
