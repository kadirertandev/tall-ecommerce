<?php

declare(strict_types=1);

namespace App\Traits;

trait ScopeFilterByTrashed
{
  public function scopeFilterByTrashed($query, $withTrashed, $onlyTrashed)
  {
    return $query
      ->when($withTrashed == true, fn($q) => $q->withTrashed())
      ->when($onlyTrashed == true, fn($q) => $q->onlyTrashed());
  }
}