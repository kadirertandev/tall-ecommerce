<?php

namespace App\Console;

use App\Models\DailyDealProduct;
use App\Models\Product;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
  /**
   * Define the application's command schedule.
   */
  protected function schedule(Schedule $schedule): void
  {
    // $schedule->command('inspire')->hourly();

    $schedule->command("app:update-daily-deal-products")->everyFiveSeconds(); // for test purposes
    // $schedule->command("app:update-daily-deal-products")->daily();

    $schedule->command("app:update-weekly-deal-products")->everyFiveSeconds(); // for test purposes
    // $schedule->command("app:update-weekly-deal-products")->sundays()->at("00.00");

    $schedule->command("app:update-popular-brands-and-categories")->sundays()->at("00.00");

  }

  /**
   * Register the commands for the application.
   */
  protected function commands(): void
  {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }
}
