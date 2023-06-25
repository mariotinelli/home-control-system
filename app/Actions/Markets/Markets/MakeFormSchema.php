<?php

namespace App\Actions\Markets\Markets;

use Filament\Forms\Components\{Grid, TextInput};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;

/** @property Model $record */
class MakeFormSchema
{
    public static function execute(
        Model $record = null,
    ): array {
        return [

            Grid::make()
                ->schema([

                    TextInput::make('name')
                        ->label('Nome')
                        ->placeholder('Nome do mercado')
                        ->autofocus()
                        ->required()
                        ->string()
                        ->unique(
                            callback: function (Unique $rule) {
                                return $rule->where('user_id', auth()->id());
                            },
                            ignoreRecord: true,
                        )
                        ->minLength(3)
                        ->maxLength(255),
                ]),
        ];
    }

}
