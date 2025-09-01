<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class AddressDataService
{
  public static array $data = [];

  public function __construct()
  {
    self::$data = Cache::rememberForever("address_data", fn() => json_decode(file_get_contents(storage_path("app/public/address.json")), (bool) JSON_PRETTY_PRINT));
  }

  public function getData()
  {
    return self::$data;
  }

  public function cities()
  {
    $cities = [];
    foreach (self::$data as $value) {
      $cities[] = $value["name"];
    }
    return $cities;
  }

  public function districts($city)
  {
    $districts = [];
    foreach (self::$data as $value) {
      if ($value["name"] == $city) {
        foreach ($value["counties"] as $countie) {
          $districts[] = $countie["name"];
        }
      }
    }
    return $districts;
  }

  public function neighborhoods($city, $district)
  {
    $neighborhoods = [];
    foreach (self::$data as $value) {
      if ($value["name"] == $city) {
        foreach ($value["counties"] as $countie) {
          if ($countie["name"] == $district) {
            foreach ($countie["districts"] as $district) {
              foreach ($district["neighborhoods"] as $neighborhood) {
                $neighborhoods[] = $neighborhood["name"];
              }
            }
          }
        }
      }
    }
    return $neighborhoods;
  }
}