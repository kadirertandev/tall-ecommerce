<?php

namespace Tests\Feature;

use App\Livewire\ProductShow;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class ProductShowTest extends TestCase
{
  use RefreshDatabase;

  private Product $product;

  private $initProperties;

  public function setUp(): void
  {
    parent::setUp();

    $this->product = Product::factory()->create();

    $this->initProperties = [
      "category_slug" => $this->product->category->slug,
      "product_slug" => $this->product->slug
    ];
  }

  public function test_component_exists_on__page()
  {
    $this->get(route("products.show", [
      "category_slug" => $this->product->category->slug,
      "product_slug" => $this->product->slug,
    ]))
      ->assertOk()
      ->assertSeeLivewire(ProductShow::class);
  }

  public function test_it_redirects_to_not_found_page_when_category_slug_is_invald()
  {
    $this->get(route("products.show", [
      ...$this->initProperties,
      "category_slug" => "invalid-category-slug"
    ]))->assertNotFound();
  }

  public function test_it_redirects_to_not_found_page_when_product_slug_is_invald()
  {
    $this->get(route("products.show", [
      ...$this->initProperties,
      "product_slug" => "invalid-product-slug"
    ]))->assertNotFound();
  }

  public function test_it_renders_product_details_correctly()
  {
    ProductReview::factory(3)
      ->for($this->product, "product")
      ->create([
        "status" => "approved"
      ]);

    #add extra fields which used in component too to render on page
    $this->product = Product::withReviewRatingAverageAndReviewCount()->find($this->product->id);

    Livewire::test(ProductShow::class, $this->initProperties)
      ->assertOk()
      ->assertSeeHtml('<img class="w-full " src="' . asset('/storage/' . $this->product->image) . '" alt="" />')
      ->assertSeeHtml($this->product->title())
      ->assertSeeTextInOrder([
        $this->product->name,
        $this->product->description,
        \App\Helpers::formatPrice($this->product->price),
        $this->product->discount_amount ? \App\Helpers::formatPrice($this->product->finalPrice()) : "",
        number_format($this->product->review_rating_average, 1),
        $this->product->review_count
      ]);
  }
}
