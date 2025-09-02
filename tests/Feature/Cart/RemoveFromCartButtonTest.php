<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Product;
use App\Models\CartItem;
use Spatie\Permission\PermissionRegistrar;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use App\Livewire\Cart\RemoveFromCartButton;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemoveFromCartButtonTest extends TestCase
{
  use RefreshDatabase;

  private User $user;
  private User $owner;
  private User $order_editor;
  private Product $product;
  private CartItem $cartItem;

  private $initProperties;

  public function setUp(): void
  {
    parent::setUp();

    $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->user = $this->createUser();

    $this->product = Product::factory()->create();

    $this->cartItem = CartItem::factory()
      ->for(Cart::factory()->for($this->user, "user"), "cart")
      ->for($this->product, "product")
      ->create();

    $this->initProperties = [
      "cartItemId" => $this->cartItem->id,
      "type" => "nav"
    ];
  }

  public function test_component_exists_on_the_cart_drawer()
  {
    $this->actingAs($this->user);

    $this->get(route("home"))
      ->assertSeeLivewire(RemoveFromCartButton::class);
  }

  public function test_component_exists_on_the_cart_page()
  {
    $this->actingAs($this->user);

    $this->get(route("auth.user.cart"))
      ->assertSeeLivewire(RemoveFromCartButton::class);
  }

  public function test_admins_can_not_remove_cart_item()
  {
    $this->artisan('db:seed', ['--class' => RolesPermissionsSeeder::class]);

    $this->owner = $this->createAdmin("owner");
    $this->order_editor = $this->createAdmin("order_editor");

    Livewire::actingAs($this->owner)->test(RemoveFromCartButton::class, $this->initProperties)
      ->call("removeFromCart", $this->cartItem->id, false)
      ->assertRedirect(route("admin.products.index"));
    $this->assertDatabaseHas("cart_items", [
      "product_id" => $this->product->id,
      "quantity" => $this->cartItem->quantity
    ]);

    Livewire::actingAs($this->order_editor)->test(RemoveFromCartButton::class, $this->initProperties)
      ->call("removeFromCart", $this->cartItem->id, false)
      ->assertDispatched("swal-fire", titleText: self::$authorizationExceptionMessage);
    $this->assertDatabaseHas("cart_items", [
      "product_id" => $this->product->id,
      "quantity" => $this->cartItem->quantity
    ]);
  }

  public function test_users_can_remove_cart_item()
  {
    $this->actingAs($this->user);

    Livewire::test(RemoveFromCartButton::class, [
      "cartItemId" => $this->cartItem->id,
      "type" => "nav"
    ])->call("removeFromCart", $this->cartItem->id, false)
      ->assertDispatched(
        "swal-fire",
        titleText: $this->product->name,
        text: __('frontend.cart.removed-from-cart')
      )
      ->assertDispatched("refresh-cart");

    $this->assertDatabaseCount("cart_items", 0);
  }

  public function test_users_can_remove_cart_item_and_add_to_favorites()
  {
    $this->actingAs($this->user);

    Livewire::test(RemoveFromCartButton::class, [
      "cartItemId" => $this->cartItem->id,
      "type" => "nav"
    ])->call("removeFromCart", cartItemId: $this->cartItem->id, addToFavorites: true)
      ->assertDispatched("added-to-favorites")
      ->assertDispatched(
        "swal-fire",
        titleText: $this->product->name,
        text: __('frontend.cart.removed-from-cart-and-added-to-favorites')
      )
      ->assertDispatched("refresh-cart");

    $this->assertDatabaseCount("cart_items", 0);
    $this->assertTrue($this->user->fresh()->favorites->contains($this->product->id));
  }

  public function test_users_can_not_remove_cart_item_does_not_belong_to_them()
  {
    $this->actingAs($this->user);

    $anotherUser = $this->createUser();
    $cart = Cart::factory()->for($anotherUser)->create();
    $this->cartItem = CartItem::factory()
      ->for($cart, "cart")
      ->for($this->product, "product")
      ->create();

    Livewire::test(RemoveFromCartButton::class, [
      "cartItemId" => $this->cartItem->id,
      "type" => "nav"
    ])->call("removeFromCart", $this->cartItem->id, false)
      ->assertDispatched("swal-fire", titleText: self::$authorizationExceptionMessage);

    $this->assertDatabaseHas("cart_items", [
      "cart_id" => $cart->id,
      "product_id" => $this->product->id,
      "quantity" => $this->cartItem->quantity
    ]);
  }
}
