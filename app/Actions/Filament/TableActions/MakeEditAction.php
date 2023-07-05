<?php

namespace App\Actions\Filament\TableActions;

use Exception;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\{Action, EditAction};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class MakeEditAction
{
    /**
     * @throws Exception
     */
    public static function execute(
        array  $form,
        string $resourceName
    ): Action {
        return EditAction::make()
            ->button()
            ->form(fn (): array => $form)
            ->disabled(fn (Model $record): bool => static::getDisableAction($record))
            ->modalHeading(fn (): View => static::getModalHeadingAction($resourceName))
            ->tooltip(fn ($action): string => static::getTooltipAction($action, $resourceName))
            ->icon(fn ($action) => $action->isDisabled() ? 'heroicon-s-lock-closed' : 'heroicon-s-pencil-alt')
            ->successNotification(fn (): Notification => static::getSuccessNotificationAction($resourceName));
    }

    protected static function getDisableAction(Model $record): bool
    {
        return !auth()->user()->can('update', $record);
    }

    protected static function getModalHeadingAction(string $resourceName): View
    {
        return view('components.app.filament.resources.modal.heading', ['title' => 'Editar ' . $resourceName]);
    }

    protected static function getTooltipAction(Action $action, string $resourceName): string
    {
        if ($action->isDisabled()) {
            $action->icon('heroicon-s-lock-closed');
        }

        return 'Editar ' . $resourceName;
    }

    protected static function getSuccessNotificationAction(string $resourceName): ?Notification
    {
        return Notification::make()
            ->body(str($resourceName)->ucfirst()->toString() . ' atualizado com sucesso.')
            ->success()
            ->send();
    }

}
