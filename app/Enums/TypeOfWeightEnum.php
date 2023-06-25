<?php

namespace App\Enums;

enum TypeOfWeightEnum: string
{
    case GRAM = 'Gramas';

    case KILOGRAM = 'Quilogramas';

    case TON = 'Toneladas';

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }
}
