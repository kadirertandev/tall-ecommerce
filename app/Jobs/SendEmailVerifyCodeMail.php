<?php

namespace App\Jobs;

use App\Mail\EmailVerifyCodeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;

class SendEmailVerifyCodeMail implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   */
  public function __construct(private $code, private $email)
  {
    //
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    Mail::to($this->email)->send(new EmailVerifyCodeMail($this->code));

  }
}
