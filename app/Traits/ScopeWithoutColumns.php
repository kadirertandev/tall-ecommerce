<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

trait ScopeWithoutColumns
{
  public function scopeWithoutColumns($query, array $excludeColumns)
  {
    $table = $this->getTable();

    $allColumns = Cache::remember("{$table}_columns", 60 * 60, function () use ($table) {
      return Schema::getColumnListing($table);
    });

    $selectedColumns = array_diff($allColumns, $excludeColumns);

    return $query->select($selectedColumns);
  }
}