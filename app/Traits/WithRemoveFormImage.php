<?php

declare(strict_types=1);

namespace App\Traits;

trait WithRemoveFormImage
{
  public function removeImage()
  {
    $this->editForm->reset("image");
    $this->editForm->resetErrorBag("image");
    $this->createForm->reset("image");
    $this->createForm->resetErrorBag("image");
  }
}