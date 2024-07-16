<?php

namespace App\Livewire\Forms\Admin;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ReviewEditForm extends Form
{
  #[Validate("required|min:5|max:50")]
  public $title;
  #[Validate("required|min:5|max:200")]
  public $comment;
}
