<?php

namespace App\Actions\Banks\CreditCards;

use Filament\Forms\Components\{Grid, TextInput};
use Illuminate\Database\Eloquent\Model;
use Leandrocfe\FilamentPtbrFormFields\PtbrMoney;

/** @property Model $record */
class MakeFormSchema
{
    public static function execute(
        Model $record = null,
    ): array {
        return [

            Grid::make([
                'default' => 1,
                'lg'      => 2,
            ])
                ->schema([

                    Grid::make([
                        'default' => 1,
                        'lg'      => 2,
                    ])
                        ->schema([
                            TextInput::make('bank')
                                ->label('Banco')
                                ->placeholder('Nome do banco')
                                ->autofocus()
                                ->required()
                                ->string()
                                ->minLength(3)
                                ->maxLength(100),

                            TextInput::make('number')
                                ->label('Número')
                                ->placeholder('Número do cartão')
                                ->required()
                                ->numeric()
                                ->unique(
                                    table: 'credit_cards',
                                    column: 'number',
                                    ignoreRecord: true,
                                )
                                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('0000 0000 0000 0000'))
                                ->rules([
                                    'min_digits:16',
                                    'max_digits:16',
                                ]),
                        ]),

                    Grid::make([
                        'default' => 1,
                        'lg'      => 3,
                    ])
                        ->schema([
                            TextInput::make('expiration')
                                ->label('Vencimento')
                                ->placeholder('12/23')
                                ->string()
                                ->required()
                                ->rules([
                                    'min:4',
                                    'max:4',
                                ])
                                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('00/00')),

                            TextInput::make('cvv')
                                ->label('CVV')
                                ->placeholder('123')
                                ->numeric()
                                ->rules([
                                    'min_digits:3',
                                    'max_digits:3',
                                ])
                                ->required()
                                ->mask(fn (TextInput\Mask $mask) => $mask->pattern('000')),

                            PtbrMoney::make('limit')
                                ->label('Limite')
                                ->required(),
                        ]),

                ]),
        ];
    }

}
