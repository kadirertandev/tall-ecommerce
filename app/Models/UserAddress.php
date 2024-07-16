<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
  use HasFactory;

  protected $fillable = [
    "user_id",
    "title",
    "city",
    "district",
    "neighborhood",
    "address_line",
    "is_default",
  ];
}
