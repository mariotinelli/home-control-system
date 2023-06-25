<?php

namespace App\Actions\Markets\Items;

use App\Enums\TypeOfWeightEnum;
use Closure;
use Filament\Tables\Columns\{BadgeColumn, TextColumn};

class MakeTableColumns
{
    public static function execute(Closure $closureTooltip): array
    {
        return [

            TextColumn::make('id')
                ->label('ID')
                ->sortable()
                ->searchable(),

            TextColumn::make('name')
                ->label('Nome do Mercado')
                ->sortable()
                ->searchable()
                ->limit(50)
                ->tooltip($closureTooltip),

            TextColumn::make('category.name')
                ->label('Categoria')
                ->sortable()
                ->searchable()->limit(50)
                ->tooltip($closureTooltip),

            BadgeColumn::make('type_weight')
                ->label('Tipo de Peso')
                ->sortable()
                ->searchable()
                ->colors([
                    'success' => TypeOfWeightEnum::GRAM->value,
                    'warning' => TypeOfWeightEnum::KILOGRAM->value,
                    'danger'  => TypeOfWeightEnum::TON->value,
                ]),

            TextColumn::make('weight')
                ->label('Peso')
                ->sortable()
                ->searchable()
                ->limit(50)
                ->tooltip($closureTooltip),

        ];
    }
}
