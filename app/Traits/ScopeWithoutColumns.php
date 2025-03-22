<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Schema;

trait ScopeWithoutColumns
{
  public function scopeWithoutColumns($query, array $excludeColumns)
  {
    $allColumns = Schema::getColumnListing($this->getTable());

    $selectedColumns = array_diff($allColumns, $excludeColumns);

    return $query->select($selectedColumns);
  }
}