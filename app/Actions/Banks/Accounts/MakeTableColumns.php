<?php

namespace App\Actions\Banks\Accounts;

use App\Enums\BankAccountTypeEnum;
use Closure;
use Filament\Tables\Columns\{BadgeColumn, TextColumn};
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

            TextColumn::make('bank_name')
                ->label('Nome do Banco')
                ->sortable()
                ->searchable()
                ->limit(50)
                ->tooltip($closureTooltip),

            BadgeColumn::make('type')
                ->label('Tipo de Conta')
                ->colors([
                    'success' => BankAccountTypeEnum::CP->value,
                    'warning' => BankAccountTypeEnum::CR->value,
                    'danger'  => BankAccountTypeEnum::PJ->value,
                ])
                ->sortable()
                ->searchable(),

            TextColumn::make('formatted_agency')
                ->label('Agência')
                ->sortable(query: function (Builder $query, string $direction): Builder {
                    return $query
                        ->orderBy('agency_number', $direction)
                        ->orderBy('agency_digit', $direction);
                })
                ->searchable(),

            TextColumn::make('formatted_number')
                ->label('Número')
                ->sortable(query: function (Builder $query, string $direction): Builder {
                    return $query
                        ->orderBy('number', $direction)
                        ->orderBy('digit', $direction);
                })
                ->searchable(),

            TextColumn::make('formatted_balance')
                ->prefix('R$ ')
                ->label('Saldo Atual')
                ->sortable(['balance'])
                ->searchable(),

        ];
    }
}
