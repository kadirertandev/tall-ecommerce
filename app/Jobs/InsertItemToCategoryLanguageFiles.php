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

class InsertItemToCategoryLanguageFiles implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  /**
   * Create a new job instance.
   */
  public function __construct(private $name, private $slug)
  {
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    $this->addTranslation("tr", $this->slug, $this->name);

    $translated = $this->translateToEnglish($this->name);
    $this->addTranslation("en", ...$translated);
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

  public function addTranslation($locale, $slug, $name)
  {
    $file = base_path("lang/{$locale}/categories.php");

    if (!File::exists($file))
      return;

    $translations = include $file;

    $translations[$this->slug] = [
      "name" => Str::headline($name),
      "slug" => $slug
    ];

    $content = "<?php\nreturn " . var_export($translations, true) . ";";

    File::put($file, $content);
  }
}
