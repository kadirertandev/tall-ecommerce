<?php

namespace Tests\Feature\Profile;

use App\Livewire\UserProfile\UserProfileReviews;
use App\Models\ProductReview;
use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class UserProfileReviewsTest extends TestCase
{
  use RefreshDatabase;

  private User $user;
  private User $owner;
  private User $admin;

  private $endPoint;

  public function setUp(): void
  {
    parent::setUp();

    $this->endPoint = route("auth.user.reviews");

    $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->user = $this->createUser();
  }

  public function test_component_exists_on_the_page()
  {
    $this->actingAs($this->user)
      ->get($this->endPoint)
      ->assertOk()
      ->assertSeeLivewire(UserProfileReviews::class);
  }

  public function test_it_redirects_to_login_page_when_unauthenticated_users_try_to_access_user_profile_reviews_page()
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

  public function test_users_can_visit_their_reviews_page()
  {
    $this->actingAs($this->user);

    Livewire::test(UserProfileReviews::class)
      ->assertStatus(200)
      ->assertSeeHtml('<h1 class="text-3xl">Reviews</h1>');
  }

  public function test_does_not_show_any_review_when_reviews_is_empty()
  {
    $this->actingAs($this->user);

    Livewire::test(UserProfileReviews::class)
      ->assertStatus(200)
      ->assertSeeHtmlInOrder([
        '<h1 class="text-3xl">Reviews</h1>',
        '<h1 class="my-2 text-xl">No reviews found.</h1>'
      ]);

    $this->assertEquals(0, $this->user->reviews()->count());
  }

  public function test_does_show_reviews_when_reviews_is_not_empty()
  {
    $this->actingAs($this->user);

    $reviews = ProductReview::factory(3)
      ->state(new Sequence(
        ["status" => "approved"],
        ["status" => "evaluating"],
        ["status" => "rejected"],
      ))
      ->create([
        "user_id" => $this->user
      ]);

    $component = Livewire::test(UserProfileReviews::class)
      ->assertStatus(200)
      ->assertSeeHtml('<h1 class="text-3xl">Reviews</h1>')
      ->assertSeeHtmlInOrder([
        '<span class="font-thin">' . Str::headline($reviews[0]->status->value) . '</span>',
        '<h1 class="font-semibold">' . $reviews[0]->title . '</h1>',
        '<span class="font-thin">' . Str::headline($reviews[1]->status->value) . '</span>',
        '<h1 class="font-semibold">' . $reviews[1]->title . '</h1>',
        '<span class="font-thin">' . Str::headline($reviews[2]->status->value) . '</span>',
        '<h1 class="font-semibold">' . $reviews[2]->title . '</h1>'
      ]);

    $this->assertDatabaseCount("product_reviews", 3);
    $this->assertEquals(3, $this->user->reviews()->count());
    $this->assertEquals(3, count($component->reviews));
  }
}
