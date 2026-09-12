<?php

namespace App\Enums;

enum TenantRoleEnum: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case SUPPORT = 'support';
    case VIEWER = 'viewer';

    /**
     * Rótulo amigável para exibição no front-end/painel.
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::MANAGER => 'Gerente',
            self::SUPPORT => 'Suporte',
            self::VIEWER => 'Visualizador',
        };
    }
}
