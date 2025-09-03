<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ProductShow extends Component
{
  public $category_slug;
  public $product_slug;
  public $breadcrumbs = [];

  public function mount($category_slug, $product_slug)
  {
    $this->category_slug = $category_slug;
    $this->product_slug = $product_slug;
    $this->breadcrumbs = [
      [
        "name" => __('categories.' . $this->category_slug . '.name'),
        "url" => route("category-slug", ["slug" => $this->category_slug])
      ],
      [
        "name" => $this->product->brand->name,
        "url" => route("brand-slug", ["slug" => $this->product->brand->name])
      ]
    ];
  }

  #[Computed()]
  public function categoryId()
  {
    $category = Category::without("brands")
      ->where("slug", $this->category_slug)
      ->firstOrFail();

    return $category->id;
  }

  #[Computed()]
  public function product()
  {
    return Product::with([
      "category" => fn($q) => $q->without("brands"),
      "brand"
    ])
      ->where([
        "slug" => $this->product_slug,
        "category_id" => $this->categoryId
      ])
      ->withoutColumns(["created_by", "updated_by", "created_at", "updated_at", "deleted_at", "deleted_by"])
      ->withReviewRatingAverageAndReviewCount()
      ->firstOrFail();
  }

  public function render()
  {
    return view('livewire.product-show')
      ->layout("components.layout")
      ->title($this->product->name . ' ' . $this->product->description);
  }
}
