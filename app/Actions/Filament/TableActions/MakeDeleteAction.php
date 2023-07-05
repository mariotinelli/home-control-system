<?php

namespace App\Actions\Filament\TableActions;

use Exception;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\{Action, DeleteAction};
use Illuminate\Database\Eloquent\Model;

class MakeDeleteAction
{
    /**
     * @throws Exception
     */
    public static function execute(
        string    $resourceName,
        ?callable $callbackAction = null
    ): Action {
        return DeleteAction::make()
            ->button()
            ->modalHeading(fn () => static::getModalHeadingAction($resourceName))
            ->disabled(fn (Model $record): bool => static::getDisableAction($record))
            ->tooltip(fn ($action) => static::getTooltipAction($action, $resourceName))
            ->action(fn (Model $record) => static::defaultAction($record, $resourceName, $callbackAction));
    }

    protected static function getDisableAction(Model $record): bool
    {
        return !auth()->user()->can('delete', $record);
    }

    protected static function defaultAction(Model $record, string $resourceName, ?callable $callbackAction = null): void
    {
        if ($callbackAction) {
            $callbackAction($record);

            return;
        }

        $record->delete();

        Notification::make()
            ->body(str($resourceName)->ucfirst()->toString() . ' deletado com sucesso.')
            ->success()
            ->send();
    }

    protected static function getTooltipAction(Action $action, string $resourceName): string
    {
        if ($action->isDisabled()) {
            $action->icon('heroicon-s-lock-closed');
        }

        return 'Deletar ' . $resourceName;
    }

    protected static function getModalHeadingAction(string $resourceName): string
    {
        return 'Deletar ' . $resourceName;
    }

}
