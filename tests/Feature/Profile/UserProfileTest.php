<?php

namespace Tests\Feature\Profile;

use App\Livewire\UserProfile\UserProfile;
use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
  use RefreshDatabase;

  private User $user;
  private User $owner;
  private User $admin;


  private $endPoint;

  public function setUp(): void
  {
    parent::setUp();

    $this->endPoint = route("auth.user.profile");

    $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->user = $this->createUser();
  }

  public function test_component_exists_on_the_page()
  {
    $this->actingAs($this->user)
      ->get($this->endPoint)
      ->assertOk()
      ->assertSeeLivewire(UserProfile::class);
  }

  public function test_it_redirects_to_login_page_when_unauthenticated_users_try_to_access_user_profile_page()
  {
    $this->get($this->endPoint)
      ->assertRedirect(route("login"));
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

  public function test_users_can_visit_their_profile_page()
  {
    $this->actingAs($this->user);

    Livewire::test(UserProfile::class)
      ->assertStatus(200)
      ->assertSet("form.first_name", $this->user->first_name)
      ->assertSet("form.last_name", $this->user->last_name)
      ->assertSet("form.email", $this->user->email)
      ->assertSeeInOrder([
        $this->user->first_name,
        $this->user->last_name,
        $this->user->email,
      ]);
  }

  public function test_users_can_upload_profile_image()
  {
    $this->actingAs($this->user);

    Storage::fake("public");

    $image = UploadedFile::fake()->image("profile_image.png")->size(500);

    $component = Livewire::test(UserProfile::class)
      ->set("form.profile_image", $image)
      ->call("update")
      ->assertDispatched(
        "swal-fire",
        titleText: "Profile updated!"
      );

    $this->assertDatabaseHas("users", [
      "id" => $this->user->id,
      "profile_image" => "profile_images/" . $image->hashName()
    ]);

    Storage::disk("public")->assertExists("profile_images/" . $image->hashName());
  }

  public function test_users_can_update_their_profile_information()
  {
    $this->actingAs($this->user);

    Storage::fake("public");

    $image = UploadedFile::fake()->image("profile_image.png")->size(500);

    $component = Livewire::test(UserProfile::class)
      ->set("form.first_name", "new first name")
      ->set("form.last_name", "new last name")
      ->set("form.email", "new_email@test.com")
      ->set("form.date_of_birth", "2025/08/24")
      ->set("form.phone_number", "5455455454")
      ->set("form.profile_image", $image)
      ->call("update")
      ->assertDispatched(
        "swal-fire",
        titleText: "Profile updated!"
      );

    $this->assertDatabaseHas("users", [
      "id" => $this->user->id,
      "first_name" => "new first name",
      "last_name" => "new last name",
      "email" => "new_email@test.com",
      "date_of_birth" => "2025/08/24",
      "phone_number" => "5455455454",
      "profile_image" => "profile_images/" . $image->hashName(),
    ]);

    Storage::disk("public")->assertExists("profile_images/" . $image->hashName());
  }

  public function test_users_can_not_update_their_profile_information_with_invalid_fields()
  {
    $this->actingAs($this->user);

    $image = UploadedFile::fake()->image("profile_image.png")->size(2048);

    Livewire::test(UserProfile::class)
      ->set("form.first_name", "ab")
      ->set("form.last_name", "")
      ->set("form.email", "new_email@")
      ->set("form.date_of_birth", "")
      ->set("form.phone_number", "+905455455454")
      ->set("form.profile_image", $image)
      ->call("update")
      ->assertHasErrors(["form.email" => "The Email field must be a valid email address."])
      ->assertHasErrors(["form.first_name" => "The First Name field must be at least 3 characters."])
      ->assertHasErrors(["form.last_name" => "The Last Name field is required."])
      ->assertHasErrors(["form.phone_number" => "The Phone number field must not be greater than 10 characters."])
      ->assertHasErrors(["form.profile_image" => "The Profile image field must not be greater than 1024 kilobytes."]);
  }

  public function test_users_can_request_to_delete_their_account_which_will_be_deleted_after_one_week()
  {
    $this->actingAs($this->user);

    Livewire::test(UserProfile::class)
      ->call("delete")
      ->assertDispatched(
        "swal-fire",
        titleText: "Account deletion request sent!",
        text: "You can cancel deletion request in 7 days."
      );

    $this->assertDatabaseHas("users", [
      "id" => $this->user->id,
      "delete_request" => true,
      "delete_request_at" => now()
    ]);

    $this->travel(6)->days();
    $this->artisan("app:delete-customers-with-delete-request")->run();
    $this->assertDatabaseHas("users", [
      "id" => $this->user->id
    ]);

    $this->travel(24)->hours();
    $this->artisan("app:delete-customers-with-delete-request")->run();
    $this->assertDatabaseMissing("users", [
      "id" => $this->user->id
    ]);
  }

  public function test_users_can_revert_account_delete_request()
  {
    $this->user = User::factory()->create([
      "delete_request" => true,
      "delete_request_at" => now()
    ]);

    $this->actingAs($this->user);

    $this->assertDatabaseHas("users", [
      "id" => $this->user->id,
      "delete_request" => true,
      "delete_request_at" => now()
    ]);

    Livewire::test(UserProfile::class)
      ->call("revertDeleteAccount")
      ->assertDispatched(
        "swal-fire",
        titleText: "Account deletion request canceled!"
      );

    $this->assertDatabaseHas("users", [
      "id" => $this->user->id,
      "delete_request" => false,
      "delete_request_at" => null
    ]);
  }
}
