<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
  use HasFactory;

  public function permissions()
  {
    return $this->belongsToMany(Permission::class, "role_has_permissions");
  }

  public function assignPermissions($permissions)
  {
    $this->permissions()->detach();
    if (is_array($permissions)) {
      foreach ($permissions as $permission) {
        $permission = Permission::where("name", $permission)->get();
        $this->permissions()->attach($permission);
      }
    }

    if ($permissions instanceof Collection) {
      foreach ($permissions as $permission) {
        $this->permissions()->attach($permission);
      }
    }
  }
}
