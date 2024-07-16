<?php

namespace App\Livewire\Auth;

use App\Jobs\SendWelcomeMail;
use App\Livewire\Forms\RegisterForm as FormsRegisterForm;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class RegisterForm extends Component
{
  public FormsRegisterForm $form;
  public function register()
  {
    $validated = $this->form->validate();
    $user = User::create($validated);
    $this->form->reset();
    // dispatch(new SendWelcomeMail($user));
    $this->redirectRoute("home");
  }
  public function render()
  {
    return view('livewire.auth.register-form');
  }
}
