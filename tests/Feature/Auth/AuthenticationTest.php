<?php

namespace Tests\Feature\Auth;

use App\Livewire\Auth\LoginForm;
use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
  use RefreshDatabase;

  private User $user;
  private User $owner;

  public function setUp(): void
  {
    parent::setUp();

    $this->artisan('db:seed', ['--class' => RolesPermissionsSeeder::class]);
    $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->user = $this->createUser();
    $this->owner = $this->createAdmin("owner");
  }

  public function test_component_exists_on_the_page()
  {
    $this->get(route("login"))
      ->assertSeeLivewire(LoginForm::class)
      ->assertSee(__("frontend.form.login-form.sign-in-to-your-account"));
  }

  public function test_users_can_authenticate_using_login_screen()
  {
    Livewire::test(LoginForm::class)
      ->set("form.email", $this->user->email)
      ->set("form.password", "password")
      ->call("login")
      ->assertRedirect(route("home"));

    $this->assertAuthenticated();
  }

  public function test_users_can_choose_to_keep_session_open_via_remember_me_option()
  {
    Livewire::test(LoginForm::class)
      ->set("form.email", $this->user->email)
      ->set("form.password", "password")
      ->set("form.remember_me", true)
      ->call("login")
      ->assertRedirect(route("home"));

    $this->assertAuthenticated();

    $cookies = $this->get(route("home"))->headers->getCookies();
    $this->assertTrue($this->hasRememberWebCookie($cookies));
  }

  public function test_admins_can_authenticate_using_login_screen()
  {
    Livewire::test(LoginForm::class)
      ->set("form.email", $this->owner->email)
      ->set("form.password", "password")
      ->call("login")
      ->assertRedirect(route("admin.dashboard"));

    $this->assertAuthenticated();
  }

  public function test_users_can_not_authenticate_with_invalid_password()
  {
    Livewire::test(LoginForm::class)
      ->set("form.email", $this->user->email)
      ->set("form.password", "wrong-password")
      ->call("login")
      ->assertHasErrors(["form.email" => "Invalid Credentials"]);
  }

  public function test_has_field_errors_when_login_form_fields_are_invalid()
  {
    Livewire::test(LoginForm::class)
      ->set("form.email", "")
      ->set("form.password", "")
      ->call("login")
      ->assertHasErrors(["form.email" => "The email field is required."])
      ->assertHasErrors(["form.password" => "The password field is required."]);

    Livewire::test(LoginForm::class)
      ->set("form.email", "not-an-email")
      ->set("form.password", "")
      ->call("login")
      ->assertHasErrors(["form.email" => "The email field must be a valid email address."])
      ->assertHasErrors(["form.password" => "The password field is required."]);
  }

  public function test_users_can_logout()
  {
    $this->actingAs($this->user)
      ->post(route("logout"))
      ->assertRedirect(route("home"))
      ->assertSessionHas("logout-success");

    $this->assertGuest();
  }

  protected function hasRememberWebCookie(array $cookieJar): bool
  {
    foreach ($cookieJar as $cookie) {
      if (Str::startsWith($cookie->getName(), 'remember_web_')) {
        return true;
      }
    }
    return false;
  }
}
