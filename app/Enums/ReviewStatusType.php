<?php

namespace App\Enums;

enum ReviewStatusType: string
{
  case APPROVED = "approved";
  case EVALUATING = "evaluating";
  case REJECTED = "rejected";
}