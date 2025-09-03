<?php

namespace App\DTOs\ProductReview;

class NewProductReviewDto
{
  public function __construct(
    private readonly int $userId,
    private readonly int $productId,
    private readonly string $title,
    private readonly string $comment,
    private readonly int $rating
  ) {
  }

  public static function fromArray(array $data)
  {
    return new self(
      userId: $data["userId"],
      productId: $data["productId"],
      title: $data["title"],
      comment: $data["comment"],
      rating: $data["rating"]
    );
  }

  public function toArray(): array
  {
    return [
      "user_id" => $this->userId,
      "product_id" => $this->productId,
      "title" => $this->title,
      "comment" => $this->comment,
      "rating" => $this->rating
    ];
  }

  public function getUserId(): int
  {
    return $this->userId;
  }
}