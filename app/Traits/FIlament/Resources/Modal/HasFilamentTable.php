<?php

namespace App\Traits\FIlament\Resources\Modal;

use App\Traits\FIlament\Resources\Modal\Tables\Actions\HasTableActions;
use App\Traits\HasLimitColumnWithTooltip;
use Closure;
use Exception;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Support\Htmlable;

trait HasFilamentTable
{
    use HasTableActions;
    use InteractsWithTable;
    use HasLimitColumnWithTooltip;

    protected function getDefaultTableSortColumn(): ?string
    {
        return static::$defaultSortColumn;
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return static::$defaultSortDirection;
    }

    protected function getTableRecordActionUsing(): ?Closure
    {
        return fn (): string => 'edit';
    }

    protected function getTableHeading(): string|Htmlable|Closure|null
    {
        return view('components.app.filament.resources.table.heading', ['title' => static::$resourcePluralName]);
    }

    /** @throws Exception */
    protected function getTableHeaderActions(): array
    {
        return [
            static::getTableCreateAction(),
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
