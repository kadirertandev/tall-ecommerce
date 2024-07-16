<?php

namespace App\Livewire\Forms\Admin;

use Livewire\Form;
use App\Constants\MimeTypes;
use Livewire\Attributes\Validate;
use Illuminate\Validation\Rules\File;

class AdminEditForm extends Form
{
  #[Validate("required")]
  public $first_name;
  #[Validate("required")]
  public $last_name;
  public $email;
  #[Validate("min:10|max:10|nullable")]
  public $phone_number;
  #[Validate("date|nullable")]
  public $date_of_birth;
  public $profile_image;
  public $userId;
  public $roleId;

  public function rules(): array
  {
    return [
      "email" => ["required", "email", "unique:users,email," . $this->userId],
      "profile_image" => [
        "nullable",
        "image",
        "max:1024",
        File::types(MimeTypes::ALLOWED_PHOTO_MIMES_UPLOAD),
      ]
    ];
  }
}
