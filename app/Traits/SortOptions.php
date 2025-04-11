<?php

declare(strict_types=1);

namespace App\Traits;

use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Lang;


trait SortOptions
{
  use HandlesOrderBy;

  public $orderFrontend;

  #[Computed()]
  public function sortOptions()
  {
    return [
      "lowestPrice" => $this->withKeys("price", "asc", Lang::get("frontend.filters.lowest-price")),
      "highestPrice" => $this->withKeys("price", "desc", Lang::get("frontend.filters.highest-price")),
      "mostLiked" => $this->withKeys("most_liked", "desc", "Most liked"),
      "newest" => $this->withKeys("created_at", "desc", Lang::get("frontend.filters.newest"))
    ];
  }

  public function withKeys(...$options)
  {
    return [
      "orderByColumn" => $options[0],
      "orderByDirection" => $options[1],
      "orderFrontend" => $options[2]
    ];
  }

  public function sortByOption($sortOption)
  {
    $this->orderByColumn = $this->validateOrderByColumn($this->sortOptions[$sortOption]["orderByColumn"]);
    $this->orderByDirection = $this->validateOrderByDirection($this->sortOptions[$sortOption]["orderByDirection"]);
    $this->orderFrontend = $this->sortOptions[$sortOption]["orderFrontend"];
  }
}