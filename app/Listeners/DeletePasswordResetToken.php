<?php

namespace App\Listeners;

use App\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class DeletePasswordResetToken
{
  /**
   * Create the event listener.
   */
  public function __construct()
  {
    //
  }

  /**
   * Handle the event.
   */
  public function handle(Login $event): void
  {
    if (session()->has("password-reset-token-can-be-deleted")) {
      DB::table("password_reset_tokens")->where("email", $event->email)->delete();

      session()->remove("password-reset-token-can-be-deleted");
    }
  }
}
