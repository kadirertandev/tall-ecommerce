<?php

namespace Tests\Feature\Cart;

use App\Livewire\Auth\LoginForm;
use Database\Seeders\RolesPermissionsSeeder;
use Tests\TestCase;
use App\Livewire\Cart\AddToCartButton;
use App\Models\Product;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class AddToCartButtonTest extends TestCase
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
      "class" => "class",
      "svg" => "svg",
    ];
  }

  public function test_admins_can_not_add_product_to_cart()
  {
    $this->artisan('db:seed', ['--class' => RolesPermissionsSeeder::class]);

    $this->owner = $this->createAdmin("owner");
    $this->order_editor = $this->createAdmin("order_editor");

    Livewire::actingAs($this->owner)->test(AddToCartButton::class, $this->initProperties)
      ->call("addToCart")
      ->assertRedirect(route("admin.products.index"));
    $this->assertDatabaseCount("carts", 0);

    Livewire::actingAs($this->order_editor)->test(AddToCartButton::class, $this->initProperties)
      ->call("addToCart")
      ->assertDispatched("swal-fire", titleText: "THIS ACTION IS UNAUTHORIZED!");
    $this->assertDatabaseCount("carts", 0);
  }

  public function test_authenticated_users_can_add_product_to_cart()
  {
    $this->actingAs($this->user);

    Livewire::test(AddToCartButton::class, $this->initProperties)->call("addToCart")
      ->assertDispatched(
        "swal-fire",
        titleText: $this->product->name,
        text: __('frontend.cart.added-to-cart')
      )
      ->assertDispatched("added-to-cart");

    $this->assertDatabaseHas("carts", [
      "user_id" => $this->user->id
    ]);
    $this->assertDatabaseCount("carts", 1);

    $this->assertDatabaseHas("cart_items", [
      "cart_id" => $this->user->cart->id,
      "product_id" => $this->product->id,
      "quantity" => 1
    ]);
    $this->assertDatabaseCount("cart_items", 1);
  }

  public function test_guests_can_add_product_to_session_cart()
  {
    Livewire::test(AddToCartButton::class, $this->initProperties)->call("addToCart")
      ->assertRedirect(route("login"))
      ->assertSessionHas("guest_cart_products", [
        $this->product->id => [
          "product_id" => $this->product->id,
          "quantity" => 1
        ]
      ]);
  }

  public function test_users_can_sync_cart_items()
  {
    $this->actingAs($this->user);

    #logged in user adds a product to cart
    Livewire::test(AddToCartButton::class, $this->initProperties)->call("addToCart");

    $this->assertDatabaseHas("carts", [
      "user_id" => $this->user->id
    ]);
    $this->assertDatabaseCount("carts", 1);

    $this->assertDatabaseHas("cart_items", [
      "cart_id" => $this->user->cart->id,
      "product_id" => $this->product->id,
      "quantity" => 1
    ]);
    $this->assertDatabaseCount("cart_items", 1);

    #user logs out
    auth()->logout();

    #guest user adds same product to session cart
    Livewire::test(AddToCartButton::class, $this->initProperties)->call("addToCart")
      ->assertRedirect(route("login"))
      ->assertSessionHas("guest_cart_products", [
        $this->product->id => [
          "product_id" => $this->product->id,
          "quantity" => 1
        ]
      ]);

    #guest user adds another product to session cart
    $anotherProduct = Product::factory()->create();
    Livewire::test(AddToCartButton::class, [
      "productId" => $anotherProduct->id,
      "class" => "class",
      "svg" => "svg",
    ])->call("addToCart")
      ->assertRedirect(route("login"))
      ->assertSessionHas("guest_cart_products", [
        $this->product->id => [
          "product_id" => $this->product->id,
          "quantity" => 1
        ],
        $anotherProduct->id => [
          "product_id" => $anotherProduct->id,
          "quantity" => 1
        ]
      ]);

    #user logs in
    $loginComponent = Livewire::test(LoginForm::class)
      ->set("form.email", $this->user->email)
      ->set("form.password", "password")
      ->call("login")
      ->assertSessionMissing("guest_cart_products");

    $this->assertDatabaseHas("carts", [
      "user_id" => $this->user->id
    ]);
    $this->assertDatabaseCount("carts", 1);

    $this->assertDatabaseHas("cart_items", [
      "cart_id" => $this->user->cart->id,
      "product_id" => $this->product->id,
      "quantity" => 2
    ]);
    $this->assertDatabaseHas("cart_items", [
      "cart_id" => $this->user->cart->id,
      "product_id" => $anotherProduct->id,
      "quantity" => 1
    ]);
    $this->assertDatabaseCount("cart_items", 2);
  }

  public function test_it_redirects_back_to_home_when_product_is_not_found()
  {
    $this->actingAs($this->user);

    Livewire::test(AddToCartButton::class, [
      "productId" => 123456,
      "class" => "class",
      "svg" => "svg",
    ])->call("addToCart")
      ->assertRedirect(route("home"));
  }

  public function test_admins_can_not_sync_cart_items()
  {
    #guest user adds product to session cart
    Livewire::test(AddToCartButton::class, [
      "productId" => $this->product->id,
      "class" => "class",
      "svg" => "svg",
    ])->call("addToCart")
      ->assertRedirect(route("login"))
      ->assertSessionHas("guest_cart_products", [
        $this->product->id => [
          "product_id" => $this->product->id,
          "quantity" => 1
        ],
        $this->product->id => [
          "product_id" => $this->product->id,
          "quantity" => 1
        ]
      ]);

    #admin user logs in
    $this->artisan('db:seed', ['--class' => RolesPermissionsSeeder::class]);
    $this->owner = $this->createAdmin("owner");

    $loginComponent = Livewire::test(LoginForm::class)
      ->set("form.email", $this->owner->email)
      ->set("form.password", "password")
      ->call("login")
      ->assertForbidden();

    $this->assertDatabaseMissing("carts", [
      "user_id" => $this->owner->id
    ]);
    $this->assertDatabaseCount("carts", 0);

    $this->assertDatabaseMissing("cart_items", [
      "cart_id" => $this->owner->cart?->id,
      "product_id" => $this->product->id,
      "quantity" => 1
    ]);
    $this->assertDatabaseCount("cart_items", 0);
  }
}
