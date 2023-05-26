<?php

namespace App\Actions\Couple\Spending\Categories;

use Filament\Forms\Components\TextInput;
use Illuminate\Validation\Rules\Unique;

class MakeFormSchema
{
    public static function execute(): array
    {
        return [

            TextInput::make('name')
                ->label('Nome')
                ->placeholder('Digite o nome da categoria')
                ->string()
                ->required()
                ->unique(
                    callback: function (Unique $rule) {
                        return $rule->where('user_id', auth()->id());
                    },
                    ignoreRecord: true,
                )
//                ->unique(
//                    table: 'couple_spending_categories',
//                    column: 'name',
//                    ignoreRecord: true,
//                    ignorable: fn($query) => $query->where('user_id', auth()->id())
//                )
                ->minLength(3)
                ->maxLength(255)
                ->validationAttribute('nome')
                ->autofocus(),

        ];
    }

}
