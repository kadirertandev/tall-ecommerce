<?php

namespace App\Livewire;

use Livewire\Component;

class LanguageDropdown extends Component
{
  public function setLocale($lang)
  {
    session()->put("locale", $lang);
    redirect(request()->header('referer'));
  }

  public function render()
  {
    return view('livewire.language-dropdown');
  }
}
