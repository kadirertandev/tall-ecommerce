<?php

declare(strict_types=1);

namespace App\Traits;

use Livewire\Attributes\Url;

trait WithTableSortAndFilter
{
  use HandlesOrderBy;

  #[Url()]
  public $keyword = "";

  public $perPage = 10;

  public function setSortBy($column)
  {
    $this->orderByColumn = $this->validateOrderByColumn($column);
    $this->orderByDirection = $this->orderByDirection == "asc" ? "desc" : "asc";
  }
}