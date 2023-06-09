<?php

namespace App\Traits\FIlament\Resources\Page;

use App\Traits\FIlament\Resources\Page\Tables\Actions\{HasCreateAction, HasDeleteAction, HasEditAction};
use App\Traits\HasLimitColumnWithTooltip;
use Closure;
use Exception;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

trait HasResourceTable
{
    use AuthorizesRequests;
    use InteractsWithTable;
    use HasLimitColumnWithTooltip;
    use HasCreateAction;
    use HasEditAction;
    use HasDeleteAction;

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
