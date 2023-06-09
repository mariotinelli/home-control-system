<?php

namespace App\Traits\FIlament\Resources\Modal\Tables\Actions;

use Exception;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\{Action, EditAction};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

trait HasEditAction
{
    /** @throws Exception */
    protected function getTableEditAction(): Action
    {

        return EditAction::make()
            ->button()
            ->form(fn (): array => $this->getFormSchema())
            ->modalHeading(fn (): View => static::getModalHeadingEditAction())
            ->tooltip(fn ($action) => static::getTooltipEditAction($action))
            ->disabled(fn (Model $record): bool => static::getDisableEditAction($record))
            ->successNotification(fn (): Notification => static::getSuccessNotificationEditAction())
            ->icon(fn ($action) => $action->isDisabled() ? 'heroicon-s-lock-closed' : 'heroicon-s-pencil-alt');
    }

    public static function getDisableEditAction(Model $record): bool
    {
        return !auth()->user()->can('update', $record);
    }

    public static function getModalHeadingEditAction(): View
    {
        return view('components.app.filament.resources.modal.heading', ['title' => 'Editar ' . static::$resourceName]);
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

        return 'Editar ' . static::$resourceName;
    }

    public static function getSuccessNotificationEditAction(): ?Notification
    {
        return Notification::make()
            ->title(static::$updateNotificationTitle ?: 'Atualização')
            ->body(static::$updateNotificationBody ?: 'O registro foi atualizado com sucesso')
            ->success()
            ->send();
    }

}
