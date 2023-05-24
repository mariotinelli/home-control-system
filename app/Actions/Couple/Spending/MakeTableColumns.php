<?php

namespace App\Actions\Couple\Spending;

use Closure;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class MakeTableColumns
{
    public static function execute(Closure $closureTooltip): array
    {
        return [

            TextColumn::make('id')
                ->label('ID')
                ->sortable()
                ->searchable(),

            TextColumn::make('category.name')
                ->label('Categoria')
                ->sortable()
                ->searchable()->limit(50)
                ->tooltip($closureTooltip),

            TextColumn::make('description')
                ->label('Descrição')
                ->sortable()
                ->searchable()->limit(50)
                ->tooltip($closureTooltip),

            TextColumn::make('amount')
                ->label('Valor')
                ->money('BRL')
                ->sortable()
                ->searchable(),

            TextColumn::make('date')
                ->label('Data')
                ->date('d/m/Y')
                ->sortable()
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query
                        ->whereRaw("DATE_FORMAT(date, '%d/%m/%Y') LIKE ?", ["%$search%"]);
                }),

        ];
    }

}
