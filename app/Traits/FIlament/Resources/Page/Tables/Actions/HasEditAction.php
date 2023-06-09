<?php

namespace App\Traits\FIlament\Resources\Page\Tables\Actions;

use Exception;
use Filament\Tables\Actions\{Action, EditAction};
use Illuminate\Database\Eloquent\Model;

trait HasEditAction
{
    /** @throws Exception */
    public static function getTableEditAction(): Action
    {
        return EditAction::make()
            ->button()
            ->tooltip(fn ($action) => static::getTooltipEditAction($action))
            ->url(fn (Model $record): string => static::getEditPageUrl($record))
            ->disabled(fn (Model $record): bool => static::getDisableEditAction($record))
            ->icon(fn ($action) => $action->isDisabled() ? 'heroicon-s-lock-closed' : 'heroicon-s-pencil-alt');
    }

    public static function getDisableEditAction(Model $record): bool
    {
        return !auth()->user()->can('update', $record);
    }

    public static function getEditPageUrl(Model $record): string
    {
        return route(static::$baseRouteName . '.edit', $record);
    }

    public static function getTooltipEditAction(Action $action): string
    {
        if ($action->isDisabled()) {
            $action->icon('heroicon-s-lock-closed');
        }

        return 'Editar ' . static::$resourceLabel;
    }

}
