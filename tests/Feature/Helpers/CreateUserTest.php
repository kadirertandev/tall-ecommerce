<?php

namespace Tests\Feature\Helpers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
  use RefreshDatabase;

  public function test_creates_user(): void
  {
    $user = $this->createUser();

    $this->assertInstanceOf(User::class, $user);

    $this->assertDatabaseHas("users", [
      "first_name" => $user->first_name,
      "last_name" => $user->last_name,
      "email" => $user->email
    ]);
  }
}
