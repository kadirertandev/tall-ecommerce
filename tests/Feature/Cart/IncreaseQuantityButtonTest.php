<?php

namespace Tests\Feature\Cart;

use App\Models\CartItem;
use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Product;
use App\Livewire\Cart\IncreaseQuantityButton;
use App\Models\Cart;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IncreaseQuantityButtonTest extends TestCase
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
      ->for(Cart::factory()->for($this->user), "cart")
      ->for($this->product, "product")
      ->create();

    $this->initProperties = [
      "cartItemId" => $this->cartItem->id,
    ];
  }

  public function test_component_exists_on_the_cart_drawer()
  {
    $this->actingAs($this->user);
    $this->cartItem = CartItem::factory()
      ->for(Cart::factory()->for($this->user), "cart")
      ->for($this->product, "product")
      ->create([
        "quantity" => 1
      ]);

    $this->actingAS($this->user)->get(route("home"))
      ->assertSeeLivewire(IncreaseQuantityButton::class);
  }

  public function test_component_exists_on_the_cart_page()
  {
    $this->actingAs($this->user);
    $this->cartItem = CartItem::factory()
      ->for(Cart::factory()->for($this->user), "cart")
      ->for($this->product, "product")
      ->create([
        "quantity" => 1
      ]);

    $this->get(route("auth.user.cart"))
      ->assertSeeLivewire(IncreaseQuantityButton::class);
  }

  public function test_admins_can_not_increase_cart_item_quantity()
  {
    $this->artisan('db:seed', ['--class' => RolesPermissionsSeeder::class]);

    $this->owner = $this->createAdmin("owner");
    $this->order_editor = $this->createAdmin("order_editor");

    Livewire::actingAs($this->owner)->test(IncreaseQuantityButton::class, $this->initProperties)
      ->call("increaseQuantity")
      ->assertRedirect(route("admin.products.index"));
    $this->assertDatabaseHas("cart_items", [
      "product_id" => $this->product->id,
      "quantity" => $this->cartItem->quantity
    ]);

    Livewire::actingAs($this->order_editor)->test(IncreaseQuantityButton::class, $this->initProperties)
      ->call("increaseQuantity")
      ->assertDispatched("swal-fire", titleText: "THIS ACTION IS UNAUTHORIZED!");
    $this->assertDatabaseHas("cart_items", [
      "product_id" => $this->product->id,
      "quantity" => $this->cartItem->quantity
    ]);
  }

  public function test_users_can_increase_cart_item_quantity()
  {
    $this->actingAs($this->user);

    Livewire::test(IncreaseQuantityButton::class, $this->initProperties)
      ->call("increaseQuantity")
      ->assertDispatched("refresh-cart");

    $this->assertDatabaseHas("cart_items", [
      "product_id" => $this->product->id,
      "quantity" => $this->cartItem->quantity + 1
    ]);
  }
}
