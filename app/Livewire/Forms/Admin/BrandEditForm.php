<?php

namespace App\Livewire\Forms\Admin;

use App\Constants\MimeTypes;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BrandEditForm extends Form
{
  #[Validate("required")]
  public $name;
  public $slug;
  public $brandId;
  public $image;

  public function rules(): array
  {
    return [
      "slug" => ["required", "unique:categories,slug," . $this->brandId],
      "image" => [
        "nullable",
        "image",
        "max:1024",
        File::types(MimeTypes::ALLOWED_PHOTO_MIMES_UPLOAD),
      ]
    ];
  }
}
