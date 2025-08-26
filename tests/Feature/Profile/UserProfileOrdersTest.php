<?php

namespace Tests\Feature\Profile;

use App\Enums\OrderStatusType;
use App\Enums\ReviewStatusType;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\UserProfileOrders;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class UserProfileOrdersTest extends TestCase
{
  use RefreshDatabase;

  private User $user;
  private User $owner;
  private User $admin;

  private $endPoint;

  public function setUp(): void
  {
    parent::setUp();

    $this->endPoint = route("auth.user.orders");

    $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();

    $this->user = $this->createUser();
  }

  public function test_component_exists_on_the_page()
  {
    $this->actingAs($this->user)
      ->get($this->endPoint)
      ->assertOk()
      ->assertSeeLivewire(UserProfileOrders::class);
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

  public function test_does_not_show_any_order_when_orders_is_empty()
  {
    $this->actingAs($this->user);

    $component = Livewire::test(UserProfileOrders::class)
      ->assertStatus(200)
      ->assertSeeHtmlInOrder([
        '<h1 class="text-3xl">Orders</h1>',
        '<h1 class="my-2 text-xl">No orders found.</h1>'
      ]);

    $this->assertEquals(0, $this->user->orders()->count());
    $this->assertEquals(0, count($component->orders));
  }

  public function test_does_show_orders_when_orders_is_not_empty()
  {
    $this->actingAs($this->user);

    $orders = Order::factory(4)
      ->state(new Sequence(
        ["status" => OrderStatusType::ORDER_PLACED->value],
        ["status" => OrderStatusType::PREPARING->value],
        ["status" => OrderStatusType::SHIPPED->value],
        ["status" => OrderStatusType::DELIVERED->value]
      ))
      ->for($this->user)
      ->has(OrderItem::factory(2))
      ->create()
      ->sortByDesc("created_at")
      ->values();

    #creates an flatten array that includes orders' and each order's items' information in sequence
    $textInOrder = function () use ($orders) {
      return $orders->map(function ($order) {
        $orderHTML = [
          $order->id,
          $order->created_at->toDayDateTimeString(),
          $order->status->value,
          $order->user->first_name . ' ' . $order->user->last_name,
          \App\Helpers::formatPrice($order->subtotal()),
        ];

        $order->orderItems->map(function ($orderItem) use (&$orderHTML, $order) {
          $orderItemHTML = [
            $orderItem->quantity,
            $orderItem->product->name,
            \App\Helpers::formatPrice($orderItem->subtotal()),
            $orderItem->original_product_price > $orderItem->price ? \App\Helpers::formatPrice($orderItem->subTotalWithoutDiscount()) : "",
          ];

          array_push($orderHTML, ...$orderItemHTML);
        });

        return $orderHTML;
      })->flatten()->toArray();
    };

    $component = Livewire::test(UserProfileOrders::class)
      ->assertStatus(200)
      ->assertSeeHtml('<h1 class="text-3xl">Orders</h1>')
      ->assertSeeTextInOrder($textInOrder());

    $this->assertDatabaseCount("orders", 4);
    $this->assertEquals(4, $this->user->orders()->count());
    $this->assertEquals(4, count($component->orders));
  }

  public function test_users_can_review_products()
  {
    $this->actingAs($this->user);

    $order = Order::factory()
      ->for($this->user)
      ->has(OrderItem::factory(1))
      ->create();

    $product = $order->orderItems()->first()->product;

    $component = Livewire::test(UserProfileOrders::class)
      ->call("openCommentModalForProduct", $product->id)
      ->assertDispatched("open-modal", name: "user-profile-order-product-comment");

    $component->set("reviewForm.title", "Review Title")
      ->set("reviewForm.comment", "Review Comment")
      ->set("rating", 3)
      ->call("createComment")
      ->assertDispatched(
        "swal-fire",
        titleText: "Review submitted successfully!",
        text: "Your review will be visible after approval."
      )->assertDispatched("close-modal", name: "user-profile-order-product-comment");

    $this->assertDatabaseHas("product_reviews", [
      "title" => "Review Title",
      "comment" => "Review Comment",
      "rating" => 3,
      "user_id" => $this->user->id,
      "product_id" => $product->id,
      "status" => ReviewStatusType::EVALUATING->value
    ]);
    $this->assertDatabaseCount("product_reviews", 1);
  }

  public function test_users_can_not_review_products_if_reviewed_already()
  {
    $this->actingAs($this->user);

    $order = Order::factory()
      ->for($this->user)
      ->has(OrderItem::factory(1))
      ->create();

    $product = $order->orderItems()->first()->product;

    $component = Livewire::test(UserProfileOrders::class)
      ->call("openCommentModalForProduct", $product->id)
      ->assertDispatched("open-modal", name: "user-profile-order-product-comment");

    $review = ProductReview::factory()->for($this->user)->create([
      "product_id" => $product->id
    ]);

    $component->set("reviewForm.title", "Review Title")
      ->set("reviewForm.comment", "Review Comment")
      ->set("rating", 3)
      ->call("createComment")
      ->assertDispatched(
        "swal-fire",
        titleText: "You can not evaluate this product",
        text: "You have already reviewed this product."
      );


    $this->assertDatabaseHas("product_reviews", [
      "title" => $review->title,
      "comment" => $review->comment,
      "rating" => $review->rating,
      "user_id" => $this->user->id,
      "product_id" => $product->id,
      "status" => $review->status
    ]);
    $this->assertDatabaseCount("product_reviews", 1);
  }
}
