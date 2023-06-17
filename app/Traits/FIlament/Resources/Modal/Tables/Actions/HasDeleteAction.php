<?php

namespace App\Traits\FIlament\Resources\Modal\Tables\Actions;

use Exception;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\{Action, DeleteAction};
use Illuminate\Database\Eloquent\Model;

trait HasDeleteAction
{
    /** @throws Exception */
    public static function getTableDeleteAction(): Action
    {

        return DeleteAction::make()
            ->button()
            ->action(fn (Model $record) => static::getDeleteAction($record))
            ->disabled(fn (Model $record): bool => static::getDisableDeleteAction($record))
            ->tooltip(fn ($action) => static::getTooltipDeleteAction($action))
            ->modalHeading(fn () => static::getModalHeadingDeleteAction())
            ->successNotification(fn () => static::getSuccessNotificationDeleteAction());
    }

    public static function beforeDelete(Model $record): void
    {
        //
    }

    public static function getDisableDeleteAction(Model $record): bool
    {
        return !auth()->user()->can('delete', $record);
    }

    public static function getDeleteAction(Model $record): void
    {
        static::beforeDelete(record: $record);

        $record->delete();
    }

    public static function getTooltipDeleteAction(Action $action): string
    {
        if ($action->isDisabled()) {
            $action->icon('heroicon-s-lock-closed');
        }

        return 'Deletar ' . static::$resourceName;
    }

    public static function getModalHeadingDeleteAction(): string
    {
        return 'Deletar ' . static::$resourceName;
    }

    public static function getSuccessNotificationDeleteAction(): Notification
    {
        return Notification::make()
            ->title('Deletar')
            ->body('Registro deletado com sucesso!')
            ->success();
    }

}
