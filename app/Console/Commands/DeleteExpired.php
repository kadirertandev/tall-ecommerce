<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DeleteExpired extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:delete-expired-mails';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    DB::table("verify_email_codes")
      ->where("expires_at", "<", Carbon::now())->delete();
  }
}
