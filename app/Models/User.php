<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'phone_number',
    'date_of_birth',
    'profile_image',
    'is_admin',
    'password',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  public function full_name()
  {
    return $this->first_name . " " . $this->last_name;
  }

  public function placeholder_initials()
  {
    return $this->first_name[0] . $this->last_name[0];
  }

  public function scopeSearch($query, $value)
  {
    return $query->where("first_name", "like", "%{$value}%")
      ->orWhere("last_name", "like", "%{$value}%")
      ->orWhere("email", "like", "%{$value}%")
      ->orWhere("phone_number", "like", "%{$value}%");
  }

  public function favorites()
  {
    return $this->belongsToMany(Product::class, "user_product_favorites");
  }

  public function cart()
  {
    return $this->hasOne(Cart::class);
  }
  public function cartItems()
  {
    return "";
  }

  public function orders()
  {
    return $this->hasMany(Order::class);
  }

  public function hasBoughtProduct(Product $product)
  {
    foreach ($this->orders as $order) {
      if ($order->products()->contains($product)) {
        return true;
      }
    }
    return false;
  }

  public function reviews()
  {
    return $this->hasMany(ProductReview::class);
  }
  public function reviewedProducts()
  {
    return $this->reviews->map(function ($review) {
      return $review->product;
    });
  }

  public function addresses()
  {
    return $this->hasMany(UserAddress::class);
  }

  public function addressesWithoutDefaultOne()
  {
    return $this->hasMany(UserAddress::class)->where("is_default", 0)->get();
  }

  public function defaultAddress()
  {
    return UserAddress::where("user_id", $this->id)->where("is_default", 1)->first();
    return $this->addresses->map(function ($address) {
      if ($address->is_default) {
        return $address;
      }
    });
  }


  /* 
  |--------------------------------------------------------------------------
  | Admin
  |--------------------------------------------------------------------------
  */

  public function isAdmin()
  {
    return (bool) $this->is_admin;
  }
  public function isCustomer()
  {
    return (bool) !$this->is_admin;
  }

  public function roles()
  {
    return $this->belongsToMany(Role::class, "model_has_roles", "user_id", "role_id");
    #before assigning new role, delete all ex roles if there are
  }

  public function role()
  {
    return $this->roles()->first();
  }

  public function assignRole($role)
  {
    $this->roles()->detach();
    if ($role instanceof Role) {
      $this->roles()->attach($role);
    } else {
      $role = Role::where("name", $role)->first();
      $this->roles()->attach($role);
    }
  }

  public function permissions()
  {
    return $this->roles()->first()->permissions();
  }
}
