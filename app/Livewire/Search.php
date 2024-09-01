<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Search extends Component
{
  public $search;
  public $thereAreResults = false;

  public $selectedCategoryTitle = "All Categories";
  public $selectedCategoryId = 0;

  public function setSelectedCategory($title, $id)
  {
    $this->selectedCategoryTitle = $title;
    $this->selectedCategoryId = $id;
  }

  public function updatedSearch()
  {
    // dd($this->search, count($this->categoryResults()), count($this->brandResults()), count($this->productResults()));
    if (count($this->categoryResults) > 0) {
      $this->thereAreResults = true;
    } elseif (count($this->brandResults) > 0) {
      $this->thereAreResults = true;
    } elseif (count($this->productResults) > 0) {
      $this->thereAreResults = true;
    } else {
      $this->thereAreResults = false;
    }
  }

  #[Computed()]
  public function categoryResults()
  {
    if (strlen($this->search) > 2) {
      $productResults = Category::where("name", "like", "%{$this->search}%")
        ->when($this->selectedCategoryId !== 0, function ($query) {
          return $query->where("id", $this->selectedCategoryId);
        })
        ->get();
      return $productResults;
    }
    return [];
  }

  #[Computed()]
  public function brandResults()
  {
    if (strlen($this->search) >= 2) {
      $productResults = Brand::where("name", "like", "%{$this->search}%")
        ->when($this->selectedCategoryId !== 0, function ($query) {
          return $query->join("category_brands", "category_brands.brand_id", "=", "brands.id")
            ->where("category_brands.category_id", $this->selectedCategoryId);
        })
        ->get();

      return $productResults;
    }
    return [];
  }

  #[Computed()]
  public function productResults()
  {
    if (strlen($this->search) > 2) {
      $productResults = Product::where("name", "like", "%{$this->search}%")
        ->when($this->selectedCategoryId !== 0, function ($query) {
          return $query->where("category_id", $this->selectedCategoryId);
        })
        ->get();
      return $productResults;
    }
    return [];
  }

  public function render()
  {
    return view('livewire.search');
  }
}
