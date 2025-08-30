<?php

namespace Tests\Feature\Profile;

use App\Livewire\AddToFavoritesButton;
use App\Livewire\UserProfile\UserProfileFavorites;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class UserProfileFavoritesTest extends TestCase
{
  use RefreshDatabase;

  private User $user;
  private User $owner;
  private User $admin;

  private $endPoint;

  public function setUp(): void
  {
    parent::setUp();

    $this->endPoint = route("auth.user.favorites");

    $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->user = $this->createUser();
  }

  public function test_component_exists_on_the_page()
  {
    $this->actingAs($this->user)
      ->get($this->endPoint)
      ->assertOk()
      ->assertSeeLivewire(UserProfileFavorites::class);
  }

  public function test_it_redirects_to_login_page_when_unauthenticated_users_try_to_access_user_profile_favorites_page()
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

  public function test_users_can_visit_their_favorites_page()
  {
    $this->actingAs($this->user);

    Livewire::test(UserProfileFavorites::class)
      ->assertStatus(200)
      ->assertSeeHtml('<h1 class="text-3xl">Favorites</h1>');
  }

  public function test_does_not_show_any_product_when_favorites_is_empty()
  {
    $this->actingAs($this->user);

    Livewire::test(UserProfileFavorites::class)
      ->assertStatus(200)
      ->assertSeeHtmlInOrder([
        '<h1 class="text-3xl">Favorites</h1>',
        'placeholder="Search within 0 products"',
        '<h1 class="my-2 text-xl">No products found.</h1>'
      ]);

    $this->assertEquals(0, $this->user->favorites()->count());
  }

  public function test_does_show_products_when_favorites_is_not_empty()
  {
    $this->actingAs($this->user);

    $products = Product::factory(3)->create();
    $this->user->favorites()->attach($products);

    $this->assertEquals(3, $this->user->favorites()->count());

    $component = Livewire::test(UserProfileFavorites::class)
      ->assertStatus(200)
      ->assertSeeHtmlInOrder([
        '<h1 class="text-3xl">Favorites</h1>',
        'placeholder="Search within 3 products"',
        $products[0]->title(),
        $products[1]->title(),
        $products[2]->title()
      ]);

    $this->assertEquals(3, count($component->favorites));
  }

  public function test_users_can_search_favorites()
  {
    $this->actingAs($this->user);

    $products = Product::factory(3)->create();
    $needleProduct1 = Product::factory()->create([
      "name" => "Needle Product's Name",
    ]);
    $needleProduct2 = Product::factory()->create([
      "description" => "Needle Product's Description",
    ]);

    $this->user->favorites()->attach($products = $products->concat([$needleProduct1, $needleProduct2]));

    $component = Livewire::test(UserProfileFavorites::class)
      ->set("search", "Needle Product")
      ->assertSeeHtmlInOrder([
        $needleProduct1->title(),
        $needleProduct2->title()
      ])
      ->assertDontSeeHtml($products[0]->title())
      ->assertDontSeeHtml($products[1]->title())
      ->assertDontSeeHtml($products[2]->title());

    $this->assertEquals(2, count($component->favorites));
  }

  public function test_users_can_filter_favorites_by_category()
  {
    $this->actingAs($this->user);

    $products = Product::factory(3)->create();
    $category = Category::factory()->create();
    $needleProduct = Product::factory()->create([
      "name" => "Needle Product's Name",
      "category_id" => $category->id
    ]);

    $this->user->favorites()->attach($products = $products->concat([$needleProduct]));

    $this->assertEquals(4, $this->user->favorites()->count());

    $component = Livewire::test(UserProfileFavorites::class)
      ->set("categoriesFilter", [$category->id])
      ->assertSeeHtml($needleProduct->title())
      ->assertDontSeeHtml($products[0]->title())
      ->assertDontSeeHtml($products[1]->title())
      ->assertDontSeeHtml($products[2]->title());

    $this->assertEquals(1, count($component->favorites));
  }

  public function test_users_can_order_favorites_by_lowest_price()
  {
    $this->actingAs($this->user);

    $lowPriceProduct = Product::factory()->create([
      "price" => 1000,
    ]);
    $mediumPriceProduct = Product::factory()->create([
      "price" => 2000,
    ]);
    $highPriceProduct = Product::factory()->create([
      "price" => 3000,
    ]);

    $products = collect([$lowPriceProduct, $mediumPriceProduct, $highPriceProduct]);

    $this->user->favorites()->attach($products->pluck("id"));
    $this->assertEquals(3, $this->user->favorites()->count());

    $component = Livewire::test(UserProfileFavorites::class)
      ->call("sortByOption", "lowestPrice")
      ->assertSeeHtmlInOrder([
        $lowPriceProduct->title(),
        $mediumPriceProduct->title(),
        $highPriceProduct->title()
      ]);
  }

  public function test_users_can_order_favorites_by_highest_price()
  {
    $this->actingAs($this->user);

    $lowPriceProduct = Product::factory()->create([
      "price" => 1000,
    ]);
    $mediumPriceProduct = Product::factory()->create([
      "price" => 2000,
    ]);
    $highPriceProduct = Product::factory()->create([
      "price" => 3000,
    ]);

    $products = collect([$lowPriceProduct, $mediumPriceProduct, $highPriceProduct]);

    $this->user->favorites()->attach($products->pluck("id"));
    $this->assertEquals(3, $this->user->favorites()->count());

    $component = Livewire::test(UserProfileFavorites::class)
      ->call("sortByOption", "highestPrice")
      ->assertSeeHtmlInOrder([
        $highPriceProduct->title(),
        $mediumPriceProduct->title(),
        $lowPriceProduct->title()
      ]);
  }

  public function test_users_can_order_favorites_by_newest()
  {
    $this->actingAs($this->user);

    $products = Product::factory(3)->create();

    $this->user->favorites()->attach($products[0], [
      "created_at" => now()->subDays(3)
    ]);
    $this->user->favorites()->attach($products[1], [
      "created_at" => now()->subDays(2)
    ]);
    $this->user->favorites()->attach($products[2], [
      "created_at" => now()->subDays(1)
    ]);

    $this->assertEquals(3, $this->user->favorites()->count());

    $component = Livewire::test(UserProfileFavorites::class)
      ->call("sortByOption", "newest")
      ->assertSeeHtmlInOrder([
        $products[2]->title(),
        $products[1]->title(),
        $products[0]->title()
      ]);
  }

  public function test_users_can_see_remove_from_favorites_button()
  {
    $this->actingAs($this->user);

    $products = Product::factory(3)->create();

    $this->user->favorites()->attach($products);

    $this->get($this->endPoint)
      ->assertSeeLivewire(AddToFavoritesButton::class);
  }

  public function test_users_can_remove_products_from_favorites()
  {
    $this->actingAs($this->user);

    $products = Product::factory(3)->create();

    $this->user->favorites()->attach($products);
    $this->assertEquals(3, $this->user->favorites()->count());

    Livewire::test(AddToFavoritesButton::class, [
      "productId" => $products[0]->id,
      "type" => "profile",
      "showLabel" => false
    ])->call("removeFromFavorites")
      ->assertDispatched("removed-from-favorites")
      ->assertDispatched(
        "swal-fire",
        titleText: "Removed from favorites"
      );

    $this->assertEquals(2, $this->user->favorites()->count());
    $this->assertDatabaseMissing("user_product_favorites", [
      "product_id" => $products[0]->id
    ]);
  }
}
