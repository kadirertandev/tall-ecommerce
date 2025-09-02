<?php

namespace Tests\Feature;

use App\Helpers\IconHelper;
use App\Livewire\UserProductFavoriteButton;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Tests\TestCase;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class UserProductFavoriteButtonTest extends TestCase
{
  use RefreshDatabase;

  private User $user;
  private User $owner;
  private User $order_editor;

  private Product $product;

  private $initProperties;

  public function setUp(): void
  {
    parent::setUp();

    $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->user = $this->createUser();

    $this->product = Product::factory()->create();

    $this->initProperties = [
      "productId" => $this->product->id,
      "type" => "show",
      "showLabel" => true
    ];
  }

  public function test_component_exists_on_the_product_show_page()
  {
    $this->get(route("products.show", [
      "category_slug" => $this->product->category->slug,
      "product_slug" => $this->product->slug,
    ]))
      ->assertOk()
      ->assertSeeText(__('frontend.favorites.add-to-favorites'))
      ->assertSeeLivewire(UserProductFavoriteButton::class);
  }

  public function test_component_exists_on_the_user_profile_favorites_page()
  {
    $this->actingAs($this->user);

    $this->user->favorites()->attach($this->product);

    $this->get(route("auth.user.favorites"))
      ->assertOk()
      ->assertSeeLivewire(UserProductFavoriteButton::class);
  }

  public function test_admins_can_not_add_product_to_favorites()
  {
    $this->artisan('db:seed', ['--class' => RolesPermissionsSeeder::class]);

    $this->owner = $this->createAdmin("owner");
    $this->order_editor = $this->createAdmin("order_editor");

    Livewire::actingAs($this->owner)->test(UserProductFavoriteButton::class, $this->initProperties)
      ->call("addToFavorites")
      ->assertRedirect(route("admin.dashboard"));

    Livewire::actingAs($this->order_editor)->test(UserProductFavoriteButton::class, $this->initProperties)
      ->call("addToFavorites")
      ->assertRedirect(route("admin.dashboard"));
  }

  public function test_it_shows_swal_error_for_guest_user_when_authorization_fails()
  {
    Livewire::test(UserProductFavoriteButton::class, $this->initProperties)
      ->call("addToFavorites")
      ->assertDispatched(
        "swal-fire",
        titleText: 'Please log in.',
        text: 'You can add product to your favorites after logging in.'
      );
  }

  public function test_users_can_add_products_to_favorites()
  {
    $this->actingAs($this->user);

    Livewire::test(UserProductFavoriteButton::class, $this->initProperties)
      ->call("addToFavorites")
      ->assertDispatched(
        "swal-fire",
        titleText: __('frontend.favorites.added-to-favorites'),
        iconHtml: IconHelper::$svgAddToFavorites,
      );

    $this->assertDatabaseCount("user_product_favorites", 1);
    $this->assertDatabaseHas("user_product_favorites", [
      "user_id" => $this->user->id,
      "product_id" => $this->product->id,
    ]);
  }

  public function test_users_can_remove_products_from_favorites()
  {
    $this->actingAs($this->user);

    $this->user->favorites()->attach($this->product);

    Livewire::test(UserProductFavoriteButton::class, $this->initProperties)
      ->call("removeFromFavorites")
      ->assertDispatched("removed-from-favorites")
      ->assertDispatched(
        "swal-fire",
        titleText: __('frontend.favorites.removed-from-favorites'),
        iconHtml: IconHelper::$svgRemoveFromFavorites,
      );

    $this->assertDatabaseCount("user_product_favorites", 0);
  }

  public function test_it_renders_add_to_favorites_button_when_user_doesnt_have_product_in_favorites()
  {
    $this->actingAs($this->user);

    $this->get(route("products.show", [
      "category_slug" => $this->product->category->slug,
      "product_slug" => $this->product->slug,
    ]))
      ->assertOk()
      ->assertSeeText(__('frontend.favorites.add-to-favorites'))
      ->assertDontSeeText(__('frontend.favorites.remove-from-favorites'))
      ->assertSeeLivewire(UserProductFavoriteButton::class);
  }

  public function test_it_renders_remove_from_favorites_button_when_user_does_have_product_in_favorites()
  {
    $this->actingAs($this->user);

    $this->user->favorites()->attach($this->product);

    $this->get(route("products.show", [
      "category_slug" => $this->product->category->slug,
      "product_slug" => $this->product->slug,
    ]))
      ->assertOk()
      ->assertSeeText(__('frontend.favorites.remove-from-favorites'))
      ->assertDontSeeText(__('frontend.favorites.add-to-favorites'))
      ->assertSeeLivewire(UserProductFavoriteButton::class);
  }
}
