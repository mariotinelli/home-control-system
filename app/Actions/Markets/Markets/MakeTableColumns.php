<?php

namespace App\Actions\Markets\Markets;

use Closure;
use Filament\Tables\Columns\{TextColumn};

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

        ];
    }
}
