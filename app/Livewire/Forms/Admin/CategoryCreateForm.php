<?php

namespace App\Livewire\Forms\Admin;

use App\Constants\MimeTypes;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Validation\Rules\File;


class CategoryCreateForm extends Form
{
  #[Validate("required")]
  public $name;
  public $slug;
  public $image;
  public $is_popular;
  #[Validate("required")]
  public $language;


  public function rules(): array
  {
    return [
      "slug" => ["required", "unique:categories,slug"],
      "image" => [
        "required",
        "image",
        "max:1024",
        File::types(MimeTypes::ALLOWED_PHOTO_MIMES_UPLOAD), // Use File::types() here
      ],
    ];
  }
}
