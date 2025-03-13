<?php

declare(strict_types=1);

namespace App\Traits;

trait WithRefreshFlowbite
{
  public function refreshFlobwite()
  {
    $this->js('setTimeout(() => { initFlowbite(); console.log("flowbite initialized") }, 300);');
  }
}