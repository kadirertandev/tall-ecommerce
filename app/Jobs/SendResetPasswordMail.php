<?php

namespace App\Jobs;

use App\Mail\ResetPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordMail implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  private $token;
  private $email;
  /**
   * Create a new job instance.
   */
  public function __construct($token, $email)
  {
    $this->token = $token;
    $this->email = $email;
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    Mail::to($this->email)->send(new ResetPasswordMail($this->token, $this->email));
  }
}
