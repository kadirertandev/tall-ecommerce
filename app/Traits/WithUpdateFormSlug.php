<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

trait WithUpdateFormSlug
{
  public function updatedCreateFormName()
  {
    $this->createForm->slug = Str::slug($this->createForm->name);
  }

  public function updatedEditFormName()
  {
    $this->editForm->slug = Str::slug($this->editForm->name);
  }
}