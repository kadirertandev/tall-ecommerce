<?php

namespace Tests\Feature;

use App\Livewire\Admin\Reviews;
use App\Livewire\ProductReviews;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class ProductReviewsTest extends TestCase
{
  use RefreshDatabase;

  private User $user;
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
      "reviewCount" => $this->product->review_count
    ];
  }

  public function test_component_exists_on_product_show_page()
  {
    $this->get(route("products.show", [
      "category_slug" => $this->product->category->slug,
      "product_slug" => $this->product->slug,
    ]))
      ->assertOk()
      ->assertSeeLivewire(ProductReviews::class);
  }

  public function test_it_renders_product_reviews_correctly()
  {
    $productReviews = ProductReview::factory(4)
      ->for($this->product, "product")
      ->create([
        "status" => "approved"
      ]);
    $productReviewsCount = $this->product->reviews()->count();

    $textInOrder = function () use ($productReviews) {
      return $productReviews
        ->take(3) #taking 3 because perPage on ProductReviews is 3. if we dont take 3 test will fail
        ->map(function ($review) {
          $orderText = [
            $review->user->first_name,
            $review->user->last_name,
            date_format($review->user->created_at, 'F Y'),
            $review->title,
            $review->created_at->toFormattedDateString(),
            $review->comment
          ];

          return $orderText;
        })->flatten()->toArray();

    };

    Livewire::test(ProductReviews::class, [
      "productId" => $this->product->id,
      "reviewCount" => $productReviewsCount
    ])
      ->assertSet("productId", $this->product->id)
      ->assertSet("reviewCount", 4)
      ->assertSeeText("Reviews
                ({$productReviewsCount})")
      ->assertSeeHtmlInOrder($productReviewsCount > 3
        ? [
          "<span>Showing</span>",
          "<span class=\"font-medium\">1</span>",
          "<span>to</span>",
          "<span class=\"font-medium\">3</span>",
          "<span>of</span>",
          "<span class=\"font-medium\">" . $productReviewsCount . "</span>",
          "<span>results</span>",
        ]
        : [])
      ->assertSeeTextInOrder($textInOrder());

  }

  public function test_it_shows_swal_error_for_guest_user_tries_to_create_review()
  {
    Livewire::test(ProductReviews::class, [
      "productId" => $this->product->id,
      "reviewCount" => 0
    ])
      ->set("title", "Review Title")
      ->set("comment", "Review Comment")
      ->set("rating", 3)
      ->call("create")
      ->assertDispatched(
        "swal-fire",
        titleText: "Please log in.",
        text: "You can evaluate the product after logging in."
      );
  }

  public function test_admins_can_not_create_product_review()
  {
    $this->artisan('db:seed', ['--class' => RolesPermissionsSeeder::class]);

    $owner = $this->createAdmin("owner");

    Livewire::actingAs($owner)->test(ProductReviews::class, [
      "productId" => $this->product->id,
      "reviewCount" => 0
    ])
      ->set("title", "Review Title")
      ->set("comment", "Review Comment")
      ->set("rating", 3)
      ->call("create")
      ->assertDispatched(
        "swal-fire",
        titleText: "You can not evaluate this product",
        text: self::$authorizationExceptionMessage
      );
  }

  public function test_users_can_not_create_review_if_they_did_not_purchase_the_product()
  {
    $this->assertDatabaseCount("orders", 0);
    $this->assertDatabaseCount("product_reviews", 0);

    Livewire::actingAs($this->user)->test(ProductReviews::class, [
      "productId" => $this->product->id,
      "reviewCount" => 0
    ])
      ->set("title", "Review Title")
      ->set("comment", "Review Comment")
      ->set("rating", 3)
      ->call("create")
      ->assertDispatched(
        "swal-fire",
        titleText: "You can not evaluate this product",
        text: "You can evaluate the product after purchasing the product."
      );
  }

  public function test_users_can_not_create_review_if_they_already_have_reviewed_before()
  {
    $productReview = ProductReview::factory()
      ->for($this->product, "product")
      ->for($this->user, "user")
      ->create([
        "status" => "approved"
      ]);

    Livewire::actingAs($this->user)->test(ProductReviews::class, [
      "productId" => $this->product->id,
      "reviewCount" => 1
    ])
      ->set("title", "Review Title")
      ->set("comment", "Review Comment")
      ->set("rating", 3)
      ->call("create")
      ->assertDispatched(
        "swal-fire",
        titleText: "You can not evaluate this product",
        text: "You have already reviewed this product."
      );
  }

  public function test_it_highlights_review_when_admins_redirect_to_view_review_on_page()
  {
    $productReview = ProductReview::factory()
      ->for($this->product, "product")
      ->create([
        "status" => "approved"
      ]);

    session(['reviewId' => $productReview->id]);

    Livewire::test(ProductReviews::class, [
      "productId" => $this->product->id,
      "reviewCount" => 1
    ])
      ->assertSet("highlightReviewId", $productReview->id)
      ->call("changeReviewsPageForHighlightedReview")
      ->assertSee("review-{$productReview->id}")
      ->assertSee("bg-[#efefef]")
      ->assertSee("animate-pulse")
      ->assertSeeHtmlInOrder([
        "<article wire:key='review-{$productReview->id}'",
        "id=\"review-{$productReview->id}\"",
        'class="py-4 my-4 border-b-2 border-red-200 shadow-xs border-s-2 ps-4 shadow-red-300 bg-[#efefef] animate-pulse"'
      ])
    ;
  }

  public function test_authorized_admins_can_click_edit_review_and_then_redirect_to_admin_reviews_page_and_can_see_automatically_opened_edit_review_modal()
  {
    $this->artisan('db:seed', ['--class' => RolesPermissionsSeeder::class]);

    $owner = $this->createAdmin("owner");

    $productReview = ProductReview::factory()
      ->for($this->product, "product")
      ->create([
        "status" => "approved"
      ]);

    Livewire::actingAs($owner)->test(ProductReviews::class, [
      "productId" => $this->product->id,
      "reviewCount" => 1
    ])->call("edit", $productReview->id)
      ->assertRedirect(route("admin.reviews.index"));

    Livewire::test(Reviews::class)
      ->assertSessionHas("review_id_to_edit", $productReview->id)
      ->call("handleSessionActions")
      ->assertSet("editForm.title", $productReview->title)
      ->assertSet("editForm.comment", $productReview->comment)
      ->assertDispatched("open-modal", name: "edit-review");
  }
}
