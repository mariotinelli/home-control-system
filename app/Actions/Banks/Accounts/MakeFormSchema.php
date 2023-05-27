<?php

namespace App\Actions\Banks\Accounts;

use App\Enums\BankAccountTypeEnum;
use Filament\Forms\Components\{Grid, Select, TextInput};
use Leandrocfe\FilamentPtbrFormFields\PtbrMoney;

class MakeFormSchema
{
    public static function execute(): array
    {
        return [

            Grid::make()
                ->schema([

                    TextInput::make('bank_name')
                        ->label('Nome do banco')
                        ->placeholder('Nome do banco')
                        ->autofocus()
                        ->required()
                        ->string()
                        ->minLength(3)
                        ->maxLength(100),

                    Select::make('type')
                        ->label('Tipo')
                        ->placeholder('Tipo da conta')
                        ->options(BankAccountTypeEnum::toArray())
                        ->required()
                        ->string()
                        ->rules([
                            'in:' . implode(',', BankAccountTypeEnum::getValues()),
                        ]),

                    TextInput::make('number')
                        ->label('Número')
                        ->placeholder('Número da conta')
                        ->required()
                        ->numeric()
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('00000000000000000000'))
                        ->unique(
                            table: 'bank_accounts',
                            column: 'number',
                            ignoreRecord: true
                        )
                        ->rules([
                            'min_digits:5',
                            'max_digits:20',
                        ]),

                    TextInput::make('digit')
                        ->label('Dígito')
                        ->placeholder('Dígito da conta')
                        ->required()
                        ->numeric()
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0'))
                        ->rules([
                            'max_digits:1',
                        ]),

                    TextInput::make('agency_number')
                        ->label('Número da agência')
                        ->placeholder('Número da agência')
                        ->required()
                        ->numeric()
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0000'))
                        ->rules([
                            'min_digits:4',
                            'max_digits:4',
                        ]),

                    TextInput::make('agency_digit')
                        ->label('Dígito da agência')
                        ->placeholder('Dígito da agência')
                        ->numeric()
                        ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0'))
                        ->rules([
                            'max_digits:1',
                        ]),

                    PtbrMoney::make('balance')
                        ->label('Saldo')
                        ->required(),

                ]),
        ];
    }

}
