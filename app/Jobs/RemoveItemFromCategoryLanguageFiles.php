<?php

namespace App\Jobs;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Stichoza\GoogleTranslate\GoogleTranslate;

class RemoveItemFromCategoryLanguageFiles implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  private $slug;

  /**
   * Create a new job instance.
   */
  public function __construct($slug)
  {
    $this->slug = $slug;
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

      if (array_key_exists("$this->slug", $categories)) {
        unset($categories["$this->slug"]);
      }
      $tr->setSource();
      $tr->setTarget("tr");
      $slug = Str::slug($tr->translate($this->slug));
      if (array_key_exists("$slug", $categories)) {
        unset($categories["$slug"]);
      }

      if (array_key_exists("$this->slug", $dictionary)) {
        unset($dictionary["$this->slug"]);
      }
      $tr->setSource();
      $tr->setTarget("en");
      $slug = Str::slug($tr->translate($this->slug));
      if (array_key_exists("$slug", $dictionary)) {
        unset($dictionary["$slug"]);
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
