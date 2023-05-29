<?php

namespace App\Traits\FIlament\Forms;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Model $record
 */
trait HasEditForm
{
    private static bool $updateMethodExists = true;

    private function initEdit(): void
    {
        $this->authorize('update', $this->record);

        $state = $this->form->getState();

        static::update($state);

        if (self::$updateMethodExists) {
            Notification::make()
                ->title(static::$resourceMenuLabel)
                ->body(static::$successUpdateNotification)
                ->success()
                ->send();
        }
    }

    public function edit(): void
    {
        self::initEdit();

        if (self::$updateMethodExists) {
            redirect()->route(static::$baseRouteName . '.index');
        }
    }

    protected static function update(array $data): void
    {
        self::$updateMethodExists = false;

        Notification::make()
            ->body('É necessário criar o método update.')
            ->warning()
            ->send();
    }

}
