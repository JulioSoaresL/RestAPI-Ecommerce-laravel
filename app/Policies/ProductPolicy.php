<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $tenant = app('currentTenant');

        if (!$tenant) return false;

        return $user->hasPermissionInTenant($tenant->id, 'products.create');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        $tenant = app('currentTenant');

        if (!$tenant || $product->tenant_id !== $tenant->id) {
            return false;
        }

        return $user->hasPermissionInTenant($tenant->id, 'products.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        $tenant = app('currentTenant');

        if (!$tenant || $product->tenant_id !== $tenant->id) {
            return false;
        }

        return $user->hasPermissionInTenant($tenant->id, 'products.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Product $product): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        return false;
    }
}
