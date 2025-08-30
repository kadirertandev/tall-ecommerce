<?php

namespace Tests\Feature\Cart;

use App\Models\CartItem;
use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Product;
use App\Livewire\Cart\DecreaseQuantityButton;
use App\Models\Cart;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DecreaseQuantityButtonTest extends TestCase
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
      ->for($this->product, "product")
      ->for(Cart::factory()->for($this->user), "cart")
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
      ->assertSeeLivewire(DecreaseQuantityButton::class);
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
      ->assertSeeLivewire(DecreaseQuantityButton::class);
  }

  public function test_admins_can_not_decrease_cart_item_quantity()
  {
    $this->artisan('db:seed', ['--class' => RolesPermissionsSeeder::class]);

    $this->owner = $this->createAdmin("owner");
    $this->order_editor = $this->createAdmin("order_editor");

    Livewire::actingAs($this->owner)->test(DecreaseQuantityButton::class, $this->initProperties)
      ->call("decreaseQuantity")
      ->assertRedirect(route("admin.products.index"));
    $this->assertDatabaseHas("cart_items", [
      "product_id" => $this->product->id,
      "quantity" => $this->cartItem->quantity
    ]);

    Livewire::actingAs($this->order_editor)->test(DecreaseQuantityButton::class, $this->initProperties)
      ->call("decreaseQuantity")
      ->assertDispatched("swal-fire", titleText: "This action is unauthorized.");
    $this->assertDatabaseHas("cart_items", [
      "product_id" => $this->product->id,
      "quantity" => $this->cartItem->quantity
    ]);
  }

  public function test_users_can_decrease_cart_item_quantity()
  {
    $this->actingAs($this->user);
    $this->cartItem = CartItem::factory()
      ->for(Cart::factory()->for($this->user), "cart")
      ->for($this->product, "product")
      ->create([
        "quantity" => 2
      ]);

    Livewire::test(DecreaseQuantityButton::class, [
      "cartItemId" => $this->cartItem->id,
    ])
      ->call("decreaseQuantity")
      ->assertDispatched("refresh-cart");

    $this->assertDatabaseHas("cart_items", [
      "product_id" => $this->product->id,
      "quantity" => $this->cartItem->quantity - 1
    ]);
  }

  public function test_users_can_not_decrease_quantity_of_cart_item_does_not_belong_to_them()
  {
    $this->actingAs($this->user);

    $anotherUser = $this->createUser();
    $cart = Cart::factory()->for($anotherUser)->create();
    $this->cartItem = CartItem::factory()
      ->for($cart, "cart")
      ->for($this->product, "product")
      ->create([
        "quantity" => 2
      ]);

    Livewire::test(DecreaseQuantityButton::class, [
      "cartItemId" => $this->cartItem->id,
    ])
      ->call("decreaseQuantity")
      ->assertDispatched("swal-fire", titleText: "This action is unauthorized.");

    $this->assertDatabaseHas("cart_items", [
      "cart_id" => $cart->id,
      "product_id" => $this->product->id,
      "quantity" => $this->cartItem->quantity
    ]);
  }

  public function test_it_asks_for_removing_when_cart_item_quantity_is_one()
  {
    $this->actingAs($this->user);
    $this->cartItem = CartItem::factory()
      ->for(Cart::factory()->for($this->user), "cart")
      ->for($this->product, "product")
      ->create([
        "quantity" => 1
      ]);

    Livewire::test(DecreaseQuantityButton::class, [
      "cartItemId" => $this->cartItem->id,
    ])
      ->call("decreaseQuantity")
      ->assertDispatched("ask-remove-from-cart", cartItemId: $this->cartItem->id);

    $this->assertDatabaseHas("cart_items", [
      "product_id" => $this->product->id,
      "quantity" => 1
    ]);
  }
}
