<?php

namespace Database\Seeders;

use App\Models\ProductReview;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    ProductReview::create([
      "title" => "Thinking to buy another one!",
      "comment" => "This is my third Invicta Pro Diver. They are just fantastic value for money. This one arrived yesterday and the first thing I did was set the time, popped on an identical strap from another Invicta and went in the shower with it to test the waterproofing.... No problems.",
      "rating" => 4,
      "user_id" => 1,
      "product_id" => 1,
      "status" => "approved"
    ]);
    ProductReview::create([
      "title" => "Impressed with the quality!",
      "comment" => "This product exceeded my expectations. The build quality is excellent, and it performs flawlessly. I highly recommend it!",
      "rating" => 3,
      "user_id" => 2,
      "product_id" => 1,
      "status" => "approved"
    ]);

    ProductReview::create([
      "title" => "Great buy for the price",
      "comment" => "I was hesitant at first, but I'm glad I took the chance on this product. It offers fantastic value for the price and has become a staple in my daily routine.",
      "rating" => 1,
      "user_id" => 3,
      "product_id" => 1,
      "status" => "approved"
    ]);

    ProductReview::create([
      "title" => "A few minor issues, but overall satisfied",
      "comment" => "While there are a couple of minor inconveniences, the product still delivers a great user experience. I would recommend it with a few reservations.",
      "rating" => 3,
      "user_id" => 4,
      "product_id" => 1,
      "status" => "approved"
    ]);

    ProductReview::create([
      "title" => "Needs improvement",
      "comment" => "Unfortunately, I'm disappointed with this product. It fell short of my expectations in several areas. I hope the manufacturer can address these issues in future versions.",
      "rating" => 2,
      "user_id" => 1,
      "product_id" => 1,
      "status" => "approved"
    ]);

    ProductReview::create([
      "title" => "Just perfect for my needs",
      "comment" => "This product is exactly what I was looking for! It checks all the boxes and has made my life much easier. I'm incredibly happy with this purchase.",
      "rating" => 3,
      "user_id" => 1,
      "product_id" => 2,
      "status" => "approved"
    ]);

    ProductReview::create([
      "title" => "Needs improvement",
      "comment" => "Unfortunately, I'm disappointed with this product. It fell short of my expectations in several areas. I hope the manufacturer can address these issues in future versions.",
      "rating" => 4,
      "user_id" => 1,
      "product_id" => 2,
      "status" => "approved"
    ]);
  }
}
