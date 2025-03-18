<?php

declare(strict_types=1);

namespace App\Traits;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Lang;


trait SortOptions
{
  #[Url()]
  public $orderBy = "created_at";

  #[Url()]
  public $sortDir = "desc";

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
      "orderBy" => $options[0],
      "sortDir" => $options[1],
      "orderFrontend" => $options[2]
    ];
  }

  public function sortByOption($sortOption)
  {
    $this->orderBy = $this->sortOptions[$sortOption]["orderBy"];
    $this->sortDir = $this->sortOptions[$sortOption]["sortDir"];
    $this->orderFrontend = $this->sortOptions[$sortOption]["orderFrontend"];
  }
}