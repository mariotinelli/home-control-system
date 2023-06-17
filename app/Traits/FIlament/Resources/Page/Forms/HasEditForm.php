<?php

namespace App\Traits\FIlament\Resources\Page\Forms;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Livewire\Redirector;

/**
 * @property Model $record
 */
trait HasEditForm
{
    public function update(): Redirector|RedirectResponse
    {
        $record = $this->record;

        $this->authorize('update', $record);

        $record = static::beforeUpdate(
            record: $record
        );

        $record->update($this->form->getState());

        $record = static::afterUpdate(
            record: $record
        );

        if (static::$sendNotification) {
            static::getNotification(
                record: $record
            );
        }

        $redirectUrl = static::getRedirectUrl(
            record: $record
        );

        if (!blank($redirectUrl)) {
            return redirect()->to($redirectUrl);
        }

        return redirect()->route(static::$baseRouteName . '.index');
    }

    public static function beforeUpdate(Model $record): Model
    {
        return $record;
    }

    public static function afterUpdate(Model $record): Model
    {
        return $record;
    }

    public static function getNotification(Model $record): ?Notification
    {
        return Notification::make()
            ->title(static::$notificationTitle ?: 'Atualização')
            ->body(static::$notificationBody ?: 'O registro foi atualizado com sucesso')
            ->success()
            ->send();
    }

    public static function getRedirectUrl(Model $record): ?string
    {
        return null;
    }

}
