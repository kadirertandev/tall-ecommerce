<?php

namespace App\Livewire;

use App\Traits\WithSweetAlert;
use App\Traits\WithTryCatch;
use Livewire\Component;

class AddToFavoritesButton extends Component
{
  use WithTryCatch;
  use WithSweetAlert;

  public $productId;
  public $type;
  public $showLabel;
  private $svgAddToFavorites = '<svg xmlns="http://www.w3.org/2000/svg" fill="red" class="w-12 h-12" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" > <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>';
  private $svgRemoveFromFavorites = '<svg xmlns="http://www.w3.org/2000/svg" fill="red" class="w-12 h-12" viewBox="0 0 24 24"><path d="M3.28 2.22a.75.75 0 1 0-1.06 1.06l1.855 1.856a5.375 5.375 0 0 0-.5 8.044l7.895 7.896a.75.75 0 0 0 1.06 0l3.744-3.742l4.445 4.447a.75.75 0 0 0 1.061-1.061zm17.152 10.959l-2.036 2.035L7.19 4.008a5.36 5.36 0 0 1 3.986 1.57l.823.824l.82-.822a5.38 5.38 0 0 1 7.613 7.599"/></svg>';

  public function mount($productId, $type, $showLabel = true)
  {
    $this->productId = $productId;
    $this->type = $type;
    $this->showLabel = $showLabel;
  }

  public function addToFavorites()
  {
    $this->tryCatch(function () {
      if (!auth()->user()->favorites()->where("product_id", $this->productId)->exists()) {
        auth()->user()->favorites()->attach($this->productId, ['created_at' => now()]);
      }

      $this->swalToast([
        "titleText" => __('frontend.favorites.added-to-favorites'),
        "text" => "",
        "iconHtml" => $this->svgAddToFavorites,
        "customClass" => [
          "icon" => "border-0!"
        ]
      ]);
    });
  }

  public function removeFromFavorites()
  {
    $this->tryCatch(function () {
      if (auth()->user()->favorites()->where("product_id", $this->productId)->exists()) {
        auth()->user()->favorites()->detach($this->productId);
      }

      $this->dispatch("removed-from-favorites");

      $this->swalToast([
        "titleText" => __('frontend.favorites.removed-from-favorites'),
        "text" => "",
        "iconHtml" => $this->svgRemoveFromFavorites,
        "customClass" => [
          "icon" => "border-0!"
        ]
      ]);
    });
  }

  public function guestError()
  {
    $this->swalError([
      'titleText' => 'Please log in.',
      'text' => 'You can add product to your favorites after logging in.'
    ]);
  }

  public function render()
  {
    return view('livewire.add-to-favorites-button');
  }
}
