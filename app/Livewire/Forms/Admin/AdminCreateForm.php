<?php

namespace App\Livewire\Forms\Admin;

use App\Constants\MimeTypes;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AdminCreateForm extends Form
{
  #[Validate("required")]
  public $first_name;
  #[Validate("required")]
  public $last_name;
  #[Validate("required|email|unique:users")]
  public $email;
  #[Validate("min:10|max:10|nullable")]
  public $phone_number;
  #[Validate("date|nullable")]
  public $date_of_birth;
  #[Validate("required|min:8")]
  public $password;
  public $profile_image;
  public $roleId;

  public function rules(): array
  {
    return [
      "profile_image" => [
        "nullable",
        "image",
        "max:1024",
        File::types(MimeTypes::ALLOWED_PHOTO_MIMES_UPLOAD), // Use File::types() here
      ],
      "roleId" => [
        "required",
        Rule::in(Role::pluck("id", "id")->toArray())
      ]
    ];
  }
}
