<?php

namespace App\Enums;

enum RoleEnum: string
{
    case AD = 'Administrador';

    case UO = 'Usuário Ouro';

    case UP = 'Usuário Prata';

    case U = 'Usuário';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }
}
