<?php

namespace App\Policies\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\AuthorizationException;

class AdminPolicy
{
  public function assignRole(User $user, User $target, Role $role): bool
  {
    #if user is target, deny
    if ($user->is($target)) {
      return false;
    }

    # if target is owner or role is owner, deny
    if ($target->getRoleName() === "owner" || $role->name === "owner") {
      return false;
    }

    # if non-owner admins try assigning super_admin role, deny
    if ($user->getRoleName() !== "owner" && $role->name === "super_admin") {
      return false;
    }

    return $user->can("assign role");
  }

  public function deleteAdmin(User $user, User $target)
  {
    if ($user->is($target)) {
      return false;
    }

    return $user->hasPermissionTo("delete admins");
  }
}
