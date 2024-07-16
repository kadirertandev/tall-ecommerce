<?php

namespace App\Enums;

enum OrderStatusType: string
{
  case ORDER_PLACED = "Sipariş Verildi";
  case PREPARING = "Hazırlanıyor";
  case SHIPPED = "Kargoya Verildi";
  case DELIVERED = "Teslim Edildi";
}