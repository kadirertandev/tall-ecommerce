<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ProductReviewForm extends Form
{
  #[Validate("required|min:5|max:50")]
  public $title;
  #[Validate("required|min:5|max:200")]
  public $comment;
}
