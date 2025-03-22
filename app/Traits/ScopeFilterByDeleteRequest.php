<?php

declare(strict_types=1);

namespace App\Traits;

trait ScopeFilterByDeleteRequest
{
  public function scopeFilterByDeleteRequest($query, $onlyDeleteRequest)
  {
    return $query
      ->when($onlyDeleteRequest == true, fn($q) => $q->where("delete_request", true));
  }
}