<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Throwable;

class UpdateCategoryLanguageFiles implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


  /**
   * Create a new job instance.
   */
  public function __construct(private $slug, private $newSlug, private $newName)
  {
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    $this->updateTranslation("tr", $this->slug, $this->newSlug, $this->newName);

    $translated = $this->translateToEnglish($this->newName);

    $this->updateTranslation("en", $this->slug, ...$translated);
  }

  public function translateToEnglish($name)
  {
    $translator = new GoogleTranslate("en", "tr");

    try {
      $nameEN = $translator->translate($name);
    } catch (Throwable $e) {
      Log::error("Translation failed for '{$name}': " . $e->getMessage());

      $nameEN = $name;
    }
    $slugEN = Str::slug($nameEN);

    return [$slugEN, $nameEN];
  }

  public function updateTranslation($locale, $slug, $newSlug, $newName)
  {
    $file = base_path("lang/{$locale}/categories.php");

    if (!File::exists($file))
      return;

    $translations = include $file;

    if (array_key_exists($slug, $translations)) {
      unset($translations[$slug]);
      unset($translations[$this->newSlug]);
    }

    $translations[$this->newSlug] = [
      "name" => Str::headline($newName),
      "slug" => $newSlug
    ];

    $content = "<?php\nreturn " . var_export($translations, true) . ";";

    File::put($file, $content);
  }
}
