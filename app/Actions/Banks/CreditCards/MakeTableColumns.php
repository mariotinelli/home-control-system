<?php

namespace App\Actions\Banks\CreditCards;

use Closure;
use Filament\Tables\Columns\{TextColumn};
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

            TextColumn::make('bank')
                ->label('Banco')
                ->sortable()
                ->searchable()
                ->limit(50)
                ->tooltip($closureTooltip),

            TextColumn::make('number')
                ->label('Número')
                ->sortable()
                ->searchable()
                ->limit(50)
                ->tooltip($closureTooltip),

            TextColumn::make('expiration')
                ->label('Data de Vencimento')
                ->sortable()
                ->searchable()
                ->limit(50)
                ->tooltip($closureTooltip),

            TextColumn::make('cvv')
                ->label('CVV')
                ->sortable()
                ->searchable()
                ->limit(50)
                ->tooltip($closureTooltip),

            TextColumn::make('formatted_limit')
                ->prefix('R$ ')
                ->label('Limite')
                ->sortable(['limit'])
                ->searchable(query: function (Builder $query, string $search): Builder {

                    $search = str($search)->replace('r$', '')
                        ->replace('R$', '')
                        ->replace('.', '')
                        ->replace(',', '.')
                        ->__toString();

                    return $query
                        ->where('limit', 'like', "%{$search}%");
                }),

            TextColumn::make('formatted_remaining_limit')
                ->prefix('R$ ')
                ->label('Limite Atual')
                ->sortable(['remaining_limit'])
                ->searchable(query: function (Builder $query, string $search): Builder {

                    $search = str($search)->replace('r$', '')
                        ->replace('R$', '')
                        ->replace('.', '')
                        ->replace(',', '.')
                        ->__toString();

                    return $query
                        ->where('remaining_limit', 'like', "%{$search}%");
                }),

        ];
    }
}
