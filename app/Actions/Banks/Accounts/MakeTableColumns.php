<?php

namespace App\Actions\Banks\Accounts;

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

            TextColumn::make('bank_name')
                ->label('Nome do Banco')
                ->sortable()
                ->searchable()
                ->limit(50)
                ->tooltip($closureTooltip),

            TextColumn::make('type')
                ->label('Tipo de Conta')
                ->sortable()
                ->searchable()
                ->limit(50)
                ->tooltip($closureTooltip),

            TextColumn::make('formatted_agency')
                ->label('Agência')
                ->sortable()
                ->searchable(),

            TextColumn::make('formatted_number')
                ->label('Número')
                ->sortable()
                ->searchable(),

            TextColumn::make('balance')
                ->label('Saldo Atual')
                ->sortable()
                ->searchable(),

        ];
    }
}
