<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\TenantRoleEnum;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function roleInTenant(int|string $tenantId): ?TenantRoleEnum
    {
        $tenant = $this->tenants()->where('tenants.id', $tenantId)->first();

        if (! $tenant || ! $tenant->pivot->role) {
            return null;
        }

        return TenantRoleEnum::tryFrom($tenant->pivot->role);
    }

    public function hasRoleInTenant(int|string $tenantId, TenantRoleEnum|array $roles): bool
    {
        $currentRole = $this->roleInTenant($tenantId);

        if (! $currentRole) {
            return false;
        }

        if (is_array($roles)) {
            return in_array($currentRole, $roles, true);
        }

        return $currentRole === $roles;
    }

    public function canAccessTenant(int|string $tenantId): bool
    {
        return $this->tenants()->where('tenants.id', $tenantId)->exists();
    }

    protected static array $rolePermissions = [
        TenantRoleEnum::ADMIN->value => [
            'products.create',
            'products.update',
            'products.delete',
            'categories.manage',
            'orders.manage',
        ],
        TenantRoleEnum::MANAGER->value => [
            'products.create',
            'products.update',
            'categories.manage',
        ],
        TenantRoleEnum::SUPPORT->value => [
            'orders.view',
        ],
    ];

    public function hasPermissionInTenant(int|string $tenantId, string $permission): bool
    {
        $role = $this->roleInTenant($tenantId);

        if (!$role) return false;

        $roleKey = $role instanceof TenantRoleEnum ? $role->value : $role;
        $permissions = static::$rolePermissions[$roleKey] ?? [];

        return in_array($permission, $permissions);
    }
}
