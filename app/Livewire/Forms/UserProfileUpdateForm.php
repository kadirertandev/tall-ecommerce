<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserProfileUpdateForm extends Form
{
  #[Validate("required|min:3|max:20")]
  public $first_name;

  #[Validate("required|min:3|max:20")]
  public $last_name;
  #[Validate("date|nullable")]
  public $date_of_birth;
  #[Validate("min:10|max:10|nullable")]
  public $phone_number;
  public function updatedDateOfBirth()
  {
    dd($this->date_of_birth);
  }
  public $email;
  #[Validate("nullable|sometimes|image|max:1024")]
  public $profile_image;

  public function rules(): array
  {
    return [
      "email" => ["required", "email", Rule::unique("users", "email")->ignore(auth()->user()->id)]
    ];
  }
  public function messages(): array
  {
    return [
      'first_name.required' => Lang::get('frontend.form.user-profile-update-form.errors.required', ['attribute' => __('frontend.form.user-profile-update-form.first-name')]),
      'first_name.min' => Lang::get('frontend.form.user-profile-update-form.errors.min', ['attribute' => __('frontend.form.user-profile-update-form.first-name')]),
      'first_name.max' => Lang::get('frontend.form.user-profile-update-form.errors.max', ['attribute' => __('frontend.form.user-profile-update-form.first-name')]),

      'last_name.required' => Lang::get('frontend.form.user-profile-update-form.errors.required', ['attribute' => __('frontend.form.user-profile-update-form.last-name')]),
      'last_name.min' => Lang::get('frontend.form.user-profile-update-form.errors.min', ['attribute' => __('frontend.form.user-profile-update-form.last-name')]),
      'last_name.max' => Lang::get('frontend.form.user-profile-update-form.errors.max', ['attribute' => __('frontend.form.user-profile-update-form.last-name')]),

      'email.required' => Lang::get('frontend.form.user-profile-update-form.errors.required', ['attribute' => __('frontend.form.user-profile-update-form.email')]),
      'email.email' => Lang::get('frontend.form.user-profile-update-form.errors.email', ['attribute' => __('frontend.form.user-profile-update-form.email')]),
      'email.unique' => Lang::get('frontend.form.user-profile-update-form.errors.unique', ['attribute' => __('frontend.form.user-profile-update-form.email')]),

      'date_of_birth.date' => Lang::get('frontend.form.user-profile-update-form.errors.date', ['attribute' => __('frontend.form.user-profile-update-form.date-of-birth')]),

      'phone_number.min' => Lang::get('frontend.form.user-profile-update-form.errors.min', ['attribute' => __('frontend.form.user-profile-update-form.phone-number')]),
      'phone_number.max' => Lang::get('frontend.form.user-profile-update-form.errors.max', ['attribute' => __('frontend.form.user-profile-update-form.phone-number')]),

      'profile_image.image' => Lang::get('frontend.form.user-profile-update-form.errors.image', ['attribute' => __('frontend.form.user-profile-update-form.profile-image')]),
      'profile_image.max' => Lang::get('frontend.form.user-profile-update-form.errors.file_max', ['attribute' => __('frontend.form.user-profile-update-form.profile-image')]),
    ];
  }
}
