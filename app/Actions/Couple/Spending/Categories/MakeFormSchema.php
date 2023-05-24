<?php

namespace App\Actions\Couple\Spending\Categories;

use Filament\Forms\Components\TextInput;

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
                ->unique('couple_spending_categories', 'name', ignoreRecord: true)
                ->minLength(3)
                ->maxLength(255)
                ->validationAttribute('nome')
                ->autofocus(),

        ];
    }

}
