<?php

namespace App\Livewire\Auth;

use App\Jobs\SendEmailVerifyCodeMail;
use App\Jobs\SendWelcomeMail;
use App\Livewire\Forms\RegisterForm as FormsRegisterForm;
use App\Models\User;
use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RegisterForm extends Component
{
  use WithSweetAlert, WithTryCatch;

  public FormsRegisterForm $form;

  #[Validate("required|max:6")]
  public $emailVerificationCode;

  #[Locked]
  public $verificationCodeSent = false;

  #[Locked]
  public $emailVerified = false;

  #[Locked]
  public $canSendEmail = true;

  private $svgEmail = '<svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" viewBox="0 0 16 16">
	<path fill="none" stroke="currentColor" stroke-linejoin="round" d="m5 4l4.5 3L14 4M2 8.5h5m-4 2h5m-3.5 2h10v-9h-10v3H1" stroke-width="1" />
</svg>';

  public function updatedFormEmail()
  {
    $this->emailVerified = false;
    $this->verificationCodeSent = false;
    $this->reset("emailVerificationCode");
  }

  #[On("countdown-over")]
  public function countdownOver()
  {
    $this->canSendEmail = true;
  }

  public function register()
  {
    $validated = $this->form->validate();

    if (!$this->emailVerified) {
      return $this->addError("form.email", "Verify your email");
    }

    $this->tryCatch(function () use ($validated) {
      $validated = array_merge(
        $validated,
        ["email_verified_at" => Carbon::now()]
      );

      $user = User::create($validated);

      $this->form->reset();

      dispatch(new SendWelcomeMail($user));

      $this->redirectRoute("home");
    });
  }

  public function sendVerificationCode()
  {
    if ($this->verificationCodeSent || !$this->canSendEmail) {
      return;
    }

    $email = $this->form->validate()["email"];

    $this->tryCatch(function () use ($email) {
      $code = Str::random(6);

      DB::table("verify_email_codes")->insert([
        "email" => $email,
        "code" => $code,
        "created_at" => Carbon::now(),
        "expires_at" => Carbon::now()->addMinutes(2)
      ]);

      dispatch(new SendEmailVerifyCodeMail($code, $this->form->email));

      $this->verificationCodeSent = true;
      $this->canSendEmail = false;
      $this->dispatch("start-countdown");

      $this->swalSuccess([
        "titleText" => "Check Your Inbox",
        "text" => "We have sent you email verification mail!",
        "showConfirmButton" => true,
        "confirmButtonText" => "Okay",
        "timer" => false,
        "iconHtml" => $this->svgEmail,
        "customClass" => [
          "icon" => "border-0!"
        ]
      ]);
    }, [
      UniqueConstraintViolationException::class => function ($e) {
        $this->swalError([
          "titleText" => "Verification email already sent!"
        ]);
      }
    ]);
  }

  public function check()
  {
    $this->resetErrorBag("emailVerificationCode");

    $this->validateOnly("emailVerificationCode");

    $row = DB::table("verify_email_codes")
      ->where("email", $this->form->email)
      ->first();

    if ($row && $row->code == $this->emailVerificationCode) {
      $this->emailVerified = true;
      $this->resetErrorBag("form.email");

      $this->swalSuccess([
        "titleText" => "Email verified successfully!",
      ]);

      DB::table("verify_email_codes")->where([
        "email" => $this->form->email,
        "code" => $this->emailVerificationCode
      ])->delete();
    } else if ($row && $row->code != $this->emailVerificationCode) {
      $this->addError("emailVerificationCode", "Verify code doesn't match!");
    } else {
      $this->verificationCodeSent = false;
      $this->addError("emailVerificationCode", "Verify code expired or not found!");
    }
  }

  public function render()
  {
    return view('livewire.auth.register-form')
      ->layout("components.guest-layout", ["title" => "Register"])
      ->section("content");
  }
}
