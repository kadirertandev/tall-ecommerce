<?php

namespace App;

class Helpers
{
  public static function formatPrice($price)
  {
    return number_format($price, 2, ',', '.');
  }
}
