<?php

namespace App\Traits\FIlament\Resources\Modal\Tables\Actions;

use Exception;
use Filament\Tables\Actions\{Action, CreateAction};
use Illuminate\Contracts\View\View;

trait HasCreateAction
{
    /** @throws Exception */
    protected function getTableCreateAction(): Action
    {
        return CreateAction::make('create')
            ->icon('heroicon-s-plus')
            ->form(fn (): array => $this->getFormSchema())
            ->action(fn (array $data) => static::store($data))
            ->color(fn (): string => static::getColorCreateAction())
            ->label(fn (): string => static::getLabelCreateAction())
            ->visible(fn (): bool => static::getVisibleCreateAction())
            ->tooltip(fn ($action) => static::getTooltipCreateAction($action))
            ->modalHeading(fn (): View => static::getModalHeadingCreateAction());
    }

    public static function getVisibleCreateAction(): bool
    {
        return auth()->user()->can('create', [static::$model]);
    }

    public static function getModalHeadingCreateAction(): View
    {
        return view('components.app.filament.resources.modal.heading', ['title' => 'Criar ' . static::$resourceName]);
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
        return static::$createButtonColor ?? 'success';
    }

    public static function getTooltipCreateAction(Action $action): string
    {
        if ($action->isDisabled()) {
            $action->icon('heroicon-s-lock-closed');
        }

        return 'Criar ' . static::$resourceName;
    }

}
