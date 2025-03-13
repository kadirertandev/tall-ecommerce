<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
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
        "name" => !Str::startsWith(__('categories.' . $this->category_slug . '.name'), 'categories.')
          ? __('categories.' . $this->category_slug . '.name')
          : __('categories.' . __('categories.dictionary.' . $this->category_slug) . '.name'),
        "url" => route("category-slug", ["slug" => $this->category_slug])
      ],
      [
        "name" => $this->product->brand->name,
        "url" => route("brand-slug", ["slug" => $this->product->brand->name])
      ]
    ];
  }

  #[Computed()]
  public function category()
  {
    return Category::where("slug", $this->category_slug)
      ->orWhere("slug", __("categories.dictionary." . $this->category_slug))
      ->firstOrFail();
  }

  #[Computed()]
  public function product()
  {
    return Product::where([
      "slug" => $this->product_slug,
      "category_id" => $this->category->id
    ])->firstOrFail();
  }

  public function render()
  {
    return view('livewire.product-show')
      ->layout("components.layout")
      ->title($this->product->name . ' ' . $this->product->description);
  }
}
