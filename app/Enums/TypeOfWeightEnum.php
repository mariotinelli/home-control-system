<?php

namespace App\Enums;

class TypeOfWeightEnum
{

    const GRAM = 'gramas';
    const KILOGRAM = 'quilogramas';
    const TON = 'toneladas';

    public static function getValues(): array
    {
        return [
            self::GRAM,
            self::KILOGRAM,
            self::TON,
        ];
    }

}
