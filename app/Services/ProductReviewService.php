<?php

namespace App\Services;

use App\DTOs\ProductReview\NewProductReviewDto;
use App\Models\ProductReview;

class ProductReviewService
{
  public function create(NewProductReviewDto $newProductReviewDto)
  {
    return ProductReview::create($newProductReviewDto->toArray());
  }
}