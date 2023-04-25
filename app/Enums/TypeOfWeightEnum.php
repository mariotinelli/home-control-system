<?php

namespace App\Enums;

class TypeOfWeightEnum
{
    public const GRAM = 'gramas';

    public const KILOGRAM = 'quilogramas';

    public const TON = 'toneladas';

    public static function getValues(): array
    {
        return [
            self::GRAM,
            self::KILOGRAM,
            self::TON,
        ];
    }
}
