<?php

namespace App\Livewire\Forms\Admin;

use App\Constants\MimeTypes;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CategoryEditForm extends Form
{
  #[Validate("required")]
  public $name;
  public $slug;
  public $image;
  public $is_popular;
  public $categoryId;
  public $categoryBrands;
  public $searchCategoryBrand = "";

  public function rules(): array
  {
    return [
      "slug" => ["required", "unique:categories,slug," . $this->categoryId],
      "image" => [
        "nullable",
        "image",
        "max:1024",
        File::types(MimeTypes::ALLOWED_PHOTO_MIMES_UPLOAD),
      ]
    ];
  }

  #[Computed()]
  public function brands()
  {
    return Brand::where("name", "like", "%{$this->searchCategoryBrand}%")->orWhere("slug", "like", "%{$this->searchCategoryBrand}%")->get();
  }
}
