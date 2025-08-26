<?php

namespace Tests\Feature\Profile;

use App\Livewire\Auth\LoginForm;
use App\Livewire\UserProfileChangePasswordForm;
use Tests\TestCase;
use Livewire\Livewire;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfileChangePasswordTest extends TestCase
{
  use RefreshDatabase;

  private User $user;
  private User $owner;
  private User $admin;

  private $endPoint;

  public function setUp(): void
  {
    parent::setUp();

    $this->endPoint = route("auth.user.change-password");

    $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->user = $this->createUser();
  }

  public function test_component_exists_on_the_page()
  {
    $this->actingAs($this->user)
      ->get($this->endPoint)
      ->assertOk()
      ->assertSeeLivewire(UserProfileChangePasswordForm::class);
  }

  public function test_admins_can_not_access()
  {
    $this->artisan('db:seed', ['--class' => RolesPermissionsSeeder::class]);

    $this->owner = $this->createAdmin("owner");
    $this->admin = $this->createAdmin("admin");

    $this->actingAs($this->owner)
      ->get($this->endPoint)
      ->assertStatus(403);

    $this->actingAs($this->admin)
      ->get($this->endPoint)
      ->assertStatus(403);
  }

  public function test_users_can_visit_change_password_page()
  {
    $this->actingAs($this->user);

    Livewire::test(UserProfileChangePasswordForm::class)
      ->assertStatus(200)
      ->assertSeeHtml('<h1 class="text-3xl">Change password</h1>')
      ->assertSeeText('Update');
  }

  public function test_users_can_change_password()
  {
    $this->actingAs($this->user);

    $component = Livewire::test(UserProfileChangePasswordForm::class)
      ->set("currentPassword", "password")
      ->set("newPassword", "new-password")
      ->call("changePassword")
      ->assertRedirect(route("login"));

    $this->assertGuest();

    $loginComponent = Livewire::test(LoginForm::class)
      ->assertSessionHas("change-password-success", true)
      ->call("handleSessionActions")
      ->assertDispatched("swal-fire", titleText: "Change Password Successful!")
      ->assertSessionMissing("change-password-success");
  }

  public function test_users_can_not_change_password_when_current_password_is_incorrect()
  {
    $this->actingAs($this->user);

    $component = Livewire::test(UserProfileChangePasswordForm::class)
      ->set("currentPassword", "incorrect-current-password")
      ->set("newPassword", "new-password")
      ->call("changePassword")
      ->assertHasErrors(["currentPassword" => "You entered your old password incompletely or incorrectly.<br>Check and try again."]);
  }

  public function test_users_can_not_change_password_when_new_password_is_invalid()
  {
    $this->actingAs($this->user);

    $component = Livewire::test(UserProfileChangePasswordForm::class)
      ->set("currentPassword", "password")
      ->set("newPassword", "pass")
      ->call("changePassword")
      ->assertHasErrors(["newPassword" => "The new password field must be at least 8 characters."]);
  }
}
