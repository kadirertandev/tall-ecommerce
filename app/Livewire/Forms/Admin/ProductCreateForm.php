<?php

namespace App\Livewire\Forms\Admin;

use App\Constants\MimeTypes;
use App\Models\Category;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Computed;

class ProductCreateForm extends Form
{
  #[Validate("required")]
  public $name;
  public $slug;
  #[Validate("required|min:20")]
  public $description;
  #[Validate("required")]
  public $price;
  public $category;
  public $brand;
  public $image;
  public $product_id;
  public function rules(): array
  {
    return [
      "slug" => ["required", "unique:products,slug," . $this->product_id],
      "image" => [
        "required",
        "image",
        "max:1024",
        File::types(MimeTypes::ALLOWED_PHOTO_MIMES_UPLOAD), // Use File::types() here
      ],
      "category" => ["required"],
      "brand" => ["required"]
    ];
  }

  public function categories()
  {
    return Category::all();
  }

  #[Computed()]
  public function brands()
  {
    return Category::find($this->category)?->brands;
  }

  public function boot()
  {
    // $this->category = Category::first()->id;
  }
}
