<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  use CreatesApplication;

  function createUser(): User
  {
    return User::factory()->create();
  }

  function createAdmin($role)
  {
    $admin = User::factory()->create([
      "is_admin" => true
    ]);

    $admin->assignRole($role);

    return $admin;
  }

  public static $authorizationExceptionMessage = "This action is unauthorized.";
}
