<?php

namespace App\Actions\Couple\Spending\Categories;

use Closure;
use Filament\Tables\Columns\TextColumn;

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
                ->label('Nome')
                ->sortable()
                ->searchable()
                ->limit(50)
                ->tooltip($closureTooltip),

        ];
    }

}
