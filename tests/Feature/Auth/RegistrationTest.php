<?php

namespace Tests\Feature\Auth;

use App\Jobs\SendEmailVerifyCodeMail;
use App\Jobs\SendWelcomeMail;
use App\Livewire\Auth\RegisterForm;
use App\Mail\EmailVerifyCodeMail;
use App\Mail\WelcomeMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
  use RefreshDatabase;

  public function test_component_exists_on_the_page()
  {
    $this->get(route("register"))
      ->assertSeeLivewire(RegisterForm::class)
      ->assertSee(__("frontend.form.register-form.create-an-account"));
  }

  public function test_users_can_register_and_recieve_welcome_email()
  {
    Bus::fake();
    Mail::fake();

    $component = Livewire::test(RegisterForm::class)
      ->set("form.first_name", "john")
      ->set("form.last_name", "doe")
      ->set("form.email", "john_doe@test.com")
      ->set("form.password", "asdfasdf")
      ->set("form.password_confirmation", "asdfasdf");

    $component->call("sendVerificationCode");

    $this->assertDatabaseHas("verify_email_codes", [
      "email" => "john_doe@test.com"
    ]);

    $code = "";

    Bus::assertDispatched(SendEmailVerifyCodeMail::class, function ($job) use (&$code) {
      $code = $job->code;

      $job->handle(); // run job manually to test mail is sent

      return $job->email === "john_doe@test.com";
    });

    Mail::assertSent(EmailVerifyCodeMail::class, function ($mail) use ($code) {
      return $mail->code === $code && $mail->hasTo("john_doe@test.com");
    });

    $component->set("emailVerificationCode", $code);

    $component->call("check");

    $this->assertDatabaseMissing("verify_email_codes", [
      "email" => "john_doe@test.com"
    ]);

    $component->call(method: "register")
      ->assertRedirect(route("home"));

    $this->assertDatabaseHas("users", [
      "first_name" => "john",
      "last_name" => "doe",
      "email" => "john_doe@test.com",
      "email_verified_at" => Carbon::now()
    ]);

    Bus::assertDispatched(SendWelcomeMail::class, function ($job) {
      $job->handle();

      return $job->user->email === "john_doe@test.com";
    });

    Mail::assertSent(WelcomeMail::class, function ($mail) {
      return $mail->user->email === "john_doe@test.com";
    });
  }

  public function test_users_can_get_email_to_verify_their_email_when_registering()
  {
    Mail::fake();

    Livewire::test(RegisterForm::class)
      ->set("form.first_name", "john")
      ->set("form.last_name", "doe")
      ->set("form.email", "john_doe@test.com")
      ->set("form.password", "asdfasdf")
      ->set("form.password_confirmation", "asdfasdf")
      ->call("sendVerificationCode");


    Mail::assertSent(
      EmailVerifyCodeMail::class,
      fn($mail) => $mail->hasTo("john_doe@test.com")
    );
  }

  public function test_registration_fails_with_invalid_fields()
  {
    Livewire::test(RegisterForm::class)
      ->set("form.email", "invalid-email@")
      ->set("form.password", "a1")
      ->call("register")
      ->assertHasErrors(["form.first_name" => "The First Name field is required."])
      ->assertHasErrors(["form.last_name" => "The Last Name field is required."])
      ->assertHasErrors(["form.email" => "The Email field must be a valid email address."])
      ->assertHasErrors(["form.password" => "The Password field must be at least 8 characters."])
      ->assertSeeInOrder([
        "The First Name field is required.",
        "The Last Name field is required.",
        "The Email field must be a valid email address.",
        "The Password field must be at least 8 characters."
      ]);
  }

  public function test_registration_fails_when_email_is_not_verified()
  {
    Livewire::test(RegisterForm::class)
      ->set("form.first_name", "john")
      ->set("form.last_name", "doe")
      ->set("form.email", "john_doe@test.com")
      ->set("form.password", "asdfasdf")
      ->set("form.password_confirmation", "asdfasdf")
      ->call("register")
      ->assertHasErrors(["form.email" => "Verify your email"])
      ->assertSeeText("Verify your email");
  }
}
