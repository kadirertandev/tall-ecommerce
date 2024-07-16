<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Stichoza\GoogleTranslate\GoogleTranslate;

class UpdateCategoryLanguageFiles implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  private $name;

  /**
   * Create a new job instance.
   */
  public function __construct($name)
  {
    $this->name = $name;
  }

  /**
   * Execute the job.
   */
  public function handle(): void
  {
    $filePaths = ['en' => './lang/en/categories.php', 'tr' => './lang/tr/categories.php'];
    foreach ($filePaths as $lang => $filePath) {
      $content = include $filePath;

      $categories = array_diff_key($content, array_flip(["dictionary"]));
      $dictionary = $content["dictionary"];

      $tr = new GoogleTranslate();
      $tr->setSource();

      if ($lang == "en") {
        $tr->setTarget("en");

        $name = Str::headline($tr->translate($this->name));
        $slug = Str::slug($name);

        $tr->setTarget("tr");
        $dictionarySlug = Str::slug($tr->translate($slug));

        $categories["{$dictionarySlug}"] = ["name" => "{$name}", "slug" => "{$slug}"];
        $dictionary["{$slug}"] = "{$dictionarySlug}";
      } else {
        $tr->setTarget("tr");

        $name = Str::headline($tr->translate($this->name));
        $slug = Str::slug($name);

        $tr->setTarget("en");
        $dictionarySlug = $tr->translate($slug);

        $categories["{$slug}"] = ["name" => "{$name}", "slug" => "{$slug}"];
        $dictionary["{$dictionarySlug}"] = "{$slug}";
      }

      $content = "<?php\n\nreturn [\n";
      foreach ($categories as $slug => $category) {
        $content .= "  \"$slug\" => [\n";
        $content .= "    \"name\" => \"" . $category['name'] . "\",\n";
        $content .= "    \"slug\" => \"" . $category['slug'] . "\"\n";
        $content .= "  ],\n";
      }

      $content .= "  \"dictionary\" => [\n";
      foreach ($dictionary as $en => $tr) {
        $content .= "    \"$en\" => \"" . $tr . "\",\n";
      }
      $content .= "  ]\n";

      $content .= "];\n";

      file_put_contents($filePath, $content);
    }
  }
}
