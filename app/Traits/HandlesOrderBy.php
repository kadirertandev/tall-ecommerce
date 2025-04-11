<?php

declare(strict_types=1);

namespace App\Traits;

use Livewire\Attributes\Url;

trait HandlesOrderBy
{
  #[Url(keep: true)]
  public $orderByColumn = "";

  #[Url(keep: true)]
  public $orderByDirection = "";

  public function validateOrderByColumn($column)
  {
    return in_array($column, $this->allowedColumns ?? [])
      ? $column
      : (in_array("created_at", $this->allowedColumns ?? [])
        ? "created_at"
        : $this->allowedColumns[0] ?? "");
  }

  public function validateOrderByDirection($orderByDirection)
  {
    return in_array(strtolower($orderByDirection), ["asc", "desc"])
      ? $orderByDirection
      : "desc";
  }

  public function validateOrderByInputs()
  {
    $this->orderByColumn = $this->validateOrderByColumn($this->orderByColumn);
    $this->orderByDirection = $this->validateOrderByDirection($this->orderByDirection);
  }
}