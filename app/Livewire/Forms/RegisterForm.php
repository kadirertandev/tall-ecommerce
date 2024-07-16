<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Lang;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RegisterForm extends Form
{
  #[Validate("required|min:3|max:20")]
  public $first_name;

  #[Validate("required|min:3|max:20")]
  public $last_name;

  #[Validate("required|email|unique:users")]
  public $email;

  #[Validate("required|min:8|confirmed")]
  public $password;

  #[Validate("same:password")]
  public $password_confirmation;

  public function messages(): array
  {
    return [
      'first_name.required' => Lang::get('frontend.form.register-form.errors.required', ['attribute' => __('frontend.form.register-form.first-name')]),
      'first_name.min' => Lang::get('frontend.form.register-form.errors.min', ['attribute' => __('frontend.form.register-form.first-name')]),
      'first_name.max' => Lang::get('frontend.form.register-form.errors.max', ['attribute' => __('frontend.form.register-form.first-name')]),

      'last_name.required' => Lang::get('frontend.form.register-form.errors.required', ['attribute' => __('frontend.form.register-form.last-name')]),
      'last_name.min' => Lang::get('frontend.form.register-form.errors.min', ['attribute' => __('frontend.form.register-form.last-name')]),
      'last_name.max' => Lang::get('frontend.form.register-form.errors.max', ['attribute' => __('frontend.form.register-form.last-name')]),

      'email.required' => Lang::get('frontend.form.register-form.errors.required', ['attribute' => __('frontend.form.register-form.email')]),
      'email.email' => Lang::get('frontend.form.register-form.errors.email', ['attribute' => __('frontend.form.register-form.email')]),
      'email.unique' => Lang::get('frontend.form.register-form.errors.unique', ['attribute' => __('frontend.form.register-form.email')]),

      'password.required' => Lang::get('frontend.form.register-form.errors.required', ['attribute' => __('frontend.form.register-form.password')]),
      'password.min' => Lang::get('frontend.form.register-form.errors.min', ['attribute' => __('frontend.form.register-form.password')]),
      'password.confirmed' => Lang::get('frontend.form.register-form.errors.confirmed', ['attribute' => __('frontend.form.register-form.password')]),
    ];
  }
}
