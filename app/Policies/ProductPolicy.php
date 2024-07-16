<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
  public function canReview(User $user, Product $product)
  {
    #check if user is customer
    #if now dont allow review
    if (!$user->isCustomer()) {
      throw new AuthorizationException('This action is unauthorized!');
    }

    #check if user has review for that product
    #if not allow review
    if ($user->reviewedProducts()->contains($product)) {
      throw new AuthorizationException('You have already reviewed this product.');
    }

    #check if user has bought product
    #if has allow review
    if (!$user->hasBoughtProduct($product)) {
      throw new AuthorizationException('You can evaluate the product after purchasing the product.');
    }

    return true; // Explicit return for clarity (optional)
  }
  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    //
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, Product $product): bool
  {
    //
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    //
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, Product $product): bool
  {
    //
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Product $product): bool
  {
    //
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, Product $product): bool
  {
    //
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, Product $product): bool
  {
    //
  }
}
