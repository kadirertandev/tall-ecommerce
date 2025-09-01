<?php

namespace App\DTOs\UserAddress;

class NewUserAddressDto
{
  public function __construct(
    private readonly int $userId,
    private readonly string $title,
    private readonly string $city,
    private readonly string $district,
    private readonly string $neighborhood,
    private readonly string $addressLine,
    private readonly bool $isDefault
  ) {
  }

  public static function fromArray(array $data)
  {
    return new self(
      userId: $data["userId"],
      title: $data['addressTitle'],
      city: $data['selectedCity'],
      district: $data['selectedDistrict'],
      neighborhood: $data['selectedNeighborhood'],
      addressLine: $data['addressLine'],
      isDefault: $data['makeDefault']
    );
  }

  public function toArray(): array
  {
    return [
      "user_id" => $this->userId,
      "title" => $this->title,
      "city" => $this->city,
      "district" => $this->district,
      "neighborhood" => $this->neighborhood,
      "address_line" => $this->addressLine,
      "is_default" => $this->isDefault
    ];
  }

  public function getIsDefault(): bool
  {
    return $this->isDefault;
  }

  public function getUserId(): int
  {
    return $this->userId;
  }
}