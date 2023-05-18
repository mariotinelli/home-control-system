<?php

namespace App\Enums;

enum BankAccountTypeEnum: string
{
    case CR = 'Corrente';

    case PO = 'Poupança';

    case PJ = 'Pessoa Jurídica';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value', 'value');
    }

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
