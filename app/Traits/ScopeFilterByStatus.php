<?php

declare(strict_types=1);

namespace App\Traits;

trait ScopeFilterByStatus
{
  public function scopeFilterByStatus($query, $statusFilter)
  {
    return $query
      ->when($statusFilter, fn($q) => $q->whereIn("status", $statusFilter));
  }
}