<?php

namespace App\Traits\FIlament\Resources\Page\Tables\Actions;

use Exception;
use Filament\Tables\Actions\{Action, CreateAction};

trait HasCreateAction
{
    /** @throws Exception */
    public static function getTableCreateAction(): Action
    {

        return CreateAction::make('create')
            ->icon('heroicon-s-plus')
            ->label(fn (): string => static::getLabelCreateAction())
            ->color(fn (): string => static::getColorCreateAction())
            ->tooltip(fn ($action) => static::getTooltipCreateAction($action))
            ->visible(fn (): bool => static::getVisibleCreateAction())
            ->url(fn (): string => static::getCreatePageUrl());
    }

    public static function getVisibleCreateAction(): bool
    {
        return auth()->user()->can('create', [static::$model]);
    }

    public static function getCreatePageUrl(): string
    {
        return route(static::$baseRouteName . '.create');
    }

    public static function getLabelCreateAction(): string
    {
        return 'Criar ' . static::$resourceName;
    }

    public static function getColorCreateAction(): string
    {
        return static::$createActionColor ?? 'success';
    }

    public static function getTooltipCreateAction(Action $action): string
    {
        if ($action->isDisabled()) {
            $action->icon('heroicon-s-lock-closed');
        }

        return 'Criar ' . static::$resourceName;
    }

}
