<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteCustomersWithDeleteRequest extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'app:delete-customers-with-delete-request';

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
    $users = User::where("delete_request", true)
      ->where("delete_request_at", "<=", now()->subDays(7))
      ->get();

    Log::info("There are {$users->count()} customers with account delete request.");

    foreach ($users as $user) {
      $user->forceDelete();
      Log::info("{$user->first_name} deleted!");
    }

    Log::info("All customers deleted!");
  }
}
