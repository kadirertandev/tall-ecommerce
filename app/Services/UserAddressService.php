<?php

namespace App\Services;

use App\DTOs\UserAddress\NewUserAddressDto;
use App\DTOs\UserAddress\UpdateUserAddressDto;
use App\Models\UserAddress;

class UserAddressService
{
  public function create(NewUserAddressDto $newUserAddressDto)
  {
    $this->handleIsDefault($newUserAddressDto);

    return UserAddress::create($newUserAddressDto->toArray());
  }

  public function update(UserAddress $userAddress, UpdateUserAddressDto $updateUserAddressDto)
  {
    $this->handleIsDefault($updateUserAddressDto);

    $userAddress->update($updateUserAddressDto->toArray());
  }

  private function handleIsDefault(NewUserAddressDto|UpdateUserAddressDto $dto)
  {
    # if makeDefault checkbox is checked
    # set all existing addresses of user to non-default before making selected address the default
    if ($dto->getIsDefault()) {
      UserAddress::where("user_id", $dto->getUserId())
        ->update(["is_default" => 0]);
    }
  }

  public function delete(UserAddress $userAddress)
  {
    $userAddress->delete();
  }
}