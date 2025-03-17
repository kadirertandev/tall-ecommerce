<?php

declare(strict_types=1);

namespace App\Traits;

use Livewire\Attributes\On;

trait WithInteractModal
{
  public function showModal(string $modalName)
  {
    $this->dispatch("open-modal", name: $modalName);
  }

  public function closeModal(string $modalName)
  {
    $this->dispatch("close-modal", name: $modalName);
  }

  #[On("modal-closed")]
  public function modalClosed($modalName)
  {
    # called after any modal being closed

    $this->resetFormInputsAndErrors(
      ["form", "createForm", "editForm", "reviewForm"]
    );
  }

  public function resetFormInputsAndErrors($properties)
  {
    foreach ($properties as $property) {
      if (
        !isset($this->$property) ||
        !property_exists(static::class, $property) ||
        !is_object($this->$property)
      ) {
        continue;
      }

      if (method_exists($this->$property::class, "reset")) {
        $this->$property->reset();
      }
      if (method_exists($this->$property::class, "resetErrorBag")) {
        $this->$property->resetErrorBag();
      }

    }
  }
}