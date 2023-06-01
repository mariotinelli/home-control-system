<?php

namespace App\Traits;

use App\Traits\FIlament\Tables\{HasDeleteAction, HasEditAction};
use Closure;
use Exception;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

trait HasFilamentTables
{
    use InteractsWithTable;
    use HasLimitColumnWithTooltip;
    use HasDeleteAction;
    use HasEditAction;

    protected function getDefaultTableSortColumn(): ?string
    {
        return static::$defaultSortColumn;
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return static::$defaultSortDirection;
    }

    protected function getTableRecordUrlUsing(): \Closure
    {
        return fn (Model $record): string => route(static::$baseRouteName . '.edit', $record);
    }

    protected function getTableHeading(): string|Htmlable|Closure|null
    {
        return view('components.app.filament.resources.table.heading', ['title' => static::$resourceMenuLabel]);
    }

    /** @throws Exception */
    protected function getTableHeaderActions(): array
    {
        return [
            CreateAction::make('create')
                ->url(fn (): string => route(static::$baseRouteName . '.create'))
                ->tooltip('Criar ' . static::$resourceLabel)
                ->icon('heroicon-s-plus')
                ->label('Criar ' . static::$resourceLabel)
                ->color(static::$createActionColor ?? 'primary')
                ->visible(fn (): bool => auth()->user()->can('create', static::$model)),
        ];
    }

    /** @throws Exception */
    protected function getTableActions(): array
    {
        return [
            static::getTableEditAction(),
            static::getTableDeleteAction(),
        ];
    }

}
