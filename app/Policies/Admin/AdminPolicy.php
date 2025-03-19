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
    # if target is owner or role is owner, deny
    if ($target->getRoleName() === "owner" || $role->name === "owner") {
      throw new AuthorizationException('This action is unauthorized!');
    }

    # if non-owner admins try assigning super_admin role, deny
    if ($user->getRoleName() !== "owner" && $role->name === "super_admin") {
      throw new AuthorizationException('This action is unauthorized!');
    }

    return $user->can("assign role");
  }
}
