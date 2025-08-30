<?php

namespace App\Policies;

use App\Models\CartItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CartItemPolicy
{
  /* Increase Quantity - Decrease Quantity */
  public function update(User $user, CartItem $cartItem): bool
  {
    return $user->is($cartItem->cart()->first()->user);
  }

  /* Remove From Cart */
  public function delete(User $user, CartItem $cartItem): bool
  {
    return $user->is($cartItem->cart()->first()->user);
  }
}
