<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Supplier;

class BulkPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function byBrand(User $user, Brand $brand): bool
    {
        return $user->is_admin || $brand->user_id === $user->id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function byCategory(User $user, Category $category): bool
    {
        return $user->is_admin || $category->user_id === $user->id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function bySupplier(User $user, Supplier $supplier): bool
    {
        return $user->is_admin || $supplier->user_id === $user->id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function updateStock(): bool
    {
        return true;
    }

}
