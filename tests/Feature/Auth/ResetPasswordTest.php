<?php

namespace Tests\Feature\Auth;

use App\Jobs\SendResetPasswordMail;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\LoginForm;
use App\Livewire\Auth\ResetPassword;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
  use RefreshDatabase;

  private User $user;

  public function setUp(): void
  {
    parent::setUp();

    $this->user = $this->createUser();
  }

  public function test_users_can_reset_password()
  {
    Bus::fake();
    Mail::fake();

    $forgotPasswordComponent = Livewire::test(ForgotPassword::class)
      ->set("email", $this->user->email)
      ->call("resetPassword");

    $this->assertDatabaseHas("password_reset_tokens", [
      "email" => $this->user->email,
      "created_at" => Carbon::now()
    ]);

    $token = "";

    Bus::assertDispatched(SendResetPasswordMail::class, function ($job) use (&$token) {
      $token = $job->token;

      $job->handle();

      return $job->email === $this->user->email;
    });

    Mail::assertSent(ResetPasswordMail::class, function ($mail) use ($token) {
      return $mail->hasTo($this->user->email) && $token === $mail->token;
    });

    $forgotPasswordComponent->assertRedirect(route("login"))
      ->assertSessionHas("reset-password-mail-sent", true);

    $resetPasswordComponent = Livewire::test(ResetPassword::class, ["token" => $token])
      ->assertSeeText(__("frontend.form.reset-password-form.set-a-new-password"));

    $resetPasswordComponent->set("email", $this->user->email)
      ->set("password", "asdfasdf")
      ->set("password_confirmation", "asdfasdf")
      ->call("resetPassword");

    $this->assertDatabaseMissing("password_reset_tokens", [
      "email" => $this->user->email,
      "token" => $token
    ]);

    $resetPasswordComponent->assertRedirect(route("login"))
      ->assertSessionHas("reset-password-success", true);
  }

  public function test_password_reset_token_record_deleted_if_user_logs_in_without_resetting_password()
  {
    Livewire::test(ForgotPassword::class)
      ->set("email", $this->user->email)
      ->call("resetPassword")
      ->assertRedirect(route("login"))
      ->assertSessionHas("reset-password-mail-sent", true);

    $this->assertDatabaseHas("password_reset_tokens", [
      "email" => $this->user->email,
      "created_at" => Carbon::now()
    ]);

    Livewire::test(LoginForm::class)
      ->call("handleSessionActions") // have to run manually because wire:init runs when component mounted in browser
      ->assertSessionHas("password-reset-token-can-be-deleted")
      ->set("form.email", $this->user->email)
      ->set("form.password", "password")
      ->call("login")
      ->assertSessionMissing("password-reset-token-can-be-deleted");

    $this->assertDatabaseMissing("password_reset_tokens", [
      "email" => $this->user->email
    ]);
  }
}
