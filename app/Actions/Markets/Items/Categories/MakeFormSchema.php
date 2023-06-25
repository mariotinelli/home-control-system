<?php

namespace App\Actions\Markets\Items\Categories;

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
                        ->placeholder('Digite o nome da categoria')
                        ->string()
                        ->required()
                        ->columnSpan(2)
                        ->unique(
                            callback: function (Unique $rule) {
                                return $rule->where('user_id', auth()->id());
                            },
                            ignoreRecord: true,
                        )
                        ->minLength(3)
                        ->maxLength(255)
                        ->autofocus(),

                ]),
        ];
    }

}
