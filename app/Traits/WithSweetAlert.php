<?php

declare(strict_types=1);

namespace App\Traits;

trait WithSweetAlert
{
  public function swalCustom($options)
  {
    $this->dispatch("swal-fire", ...$options);
  }

  public function swalSuccess($options)
  {
    $options = array_merge(
      [
        "icon" => "success"
      ],
      $options
    );

    $this->dispatch("swal-fire", ...$options);
  }

  public function swalError($options)
  {
    $options = array_merge(
      [
        "icon" => "error"
      ],
      $options
    );

    $this->dispatch("swal-fire", ...$options);
  }

  public function swalQuestion($options)
  {
    $options = array_merge(
      [
        "icon" => "question",
        "timer" => false,
        "showCloseButton" => true,

        "showConfirmButton" => true,
        "showDenyButton" => true,

        "onConfirmParameters" => [],
        "onDenyParameters" => []
      ],
      $options
    );

    $this->dispatch("swal-fire", ...$options);
  }

  public function swalToast($options)
  {
    $options = array_merge(
      [
        "toast" => true,
        "position" => "top-end",
        "timer" => 1000,
      ],
      $options
    );

    $this->dispatch("swal-fire", ...$options);
  }

  public function swalTemplateSomethingWentWrong()
  {
    $options = [
      "icon" => "error",
      "titleText" => "Something went wrong!"
    ];

    $this->dispatch("swal-fire", ...$options);
  }

  public function swalTemplateAssociatedExistsError($options)
  {
    $options = array_merge(
      [
        "icon" => "error",
        "timer" => false,
        "allowOutsideClick" => false,
        "showConfirmButton" => true,
        "confirmButtonText" => "OKAY",
        "confirmButtonColor" => "#0694a2",
        "customClass" => [
          "title" => "text-nowrap!",
          "htmlContainer" => "text-nowrap!",
          "popup" => "min-w-max!"
        ]
      ],
      $options
    );

    $this->dispatch("swal-fire", ...$options);
  }
}