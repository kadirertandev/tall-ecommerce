<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\File;

class RemoveItemFromCategoryLanguageFiles implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   */
  public function __construct(private $slug)
  {
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    $this->deleteCategoryItem("en", $this->slug);
    $this->deleteCategoryItem("tr", $this->slug);
  }

  public function deleteCategoryItem($locale, $slug)
  {
    $file = base_path("lang/{$locale}/categories.php");

    if (!File::exists($file))
      return;

    $translations = include $file;

    if (!is_array($translations) || count($translations) === 0)
      return;

    if (array_key_exists($slug, $translations))
      unset($translations[$slug]);

    $content = "<?php\nreturn " . var_export($translations, true) . ";";

    File::put($file, $content);
  }
}
