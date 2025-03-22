<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

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
      ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), "like", "%{$value}%")
      ->orWhere("email", "like", "%{$value}%")
      ->orWhere("phone_number", "like", "%{$value}%");
  }

  public function scopeWithRoleIdAndRoleName($query)
  {
    return $query->select("users.*")->addSelect([
      'model_has_roles.role_id',
      "role_name" => Role::select('name')->whereColumn("roles.id", "model_has_roles.role_id")
    ]);
  }

  public function scopeFilterByTrashed($query, $withTrashed, $onlyTrashed)
  {
    return $query
      ->when($withTrashed == true, fn($q) => $q->withTrashed())
      ->when($onlyTrashed == true, fn($q) => $q->onlyTrashed());
  }

  public function scopeFilterByRole($query, $roleFilter)
  {
    return $query
      ->when($roleFilter, fn($q) => $q->whereIn("role_id", $roleFilter));
  }

  public function scopeSortByColumn($query, $sortBy, $sortDir)
  {
    return $query
      ->when(
        $sortBy && $sortDir,
        function ($q) use ($sortBy, $sortDir) {
          $q->when($sortBy == "full_name", fn($q) => $q->orderBy(DB::raw("CONCAT(first_name, ' ', last_name)"), $sortDir))
            ->when($sortBy != "full_name", fn($q) => $q->orderBy($sortBy, $sortDir));
        }
      );
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

  public function getRoleId()
  {
    return $this->roles()->first()->id ?? null;
  }

  public function getRoleName()
  {
    return $this->roles()->first()->name ?? null;
  }
}
