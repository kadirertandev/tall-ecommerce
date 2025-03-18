<?php

declare(strict_types=1);

namespace App\Traits;

trait WithTableSortAndFilter
{
  public $keyword = "";
  public $perPage = 10;
  public $sortDir = "";
  public $sortBy = "";

  public function setSortBy($column)
  {
    $this->sortBy = $column;
    $this->sortDir = $this->sortDir == "asc" ? "desc" : "asc";
  }
}