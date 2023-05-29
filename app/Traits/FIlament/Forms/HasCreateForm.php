<?php

namespace App\Traits\FIlament\Forms;

use Filament\Notifications\Notification;

trait HasCreateForm
{
    private static bool $createMethodExists = true;

    private function initCreate(): void
    {
        $this->authorize('create', [static::$model]);

        $state = $this->form->getState();

        static::store($state);

        if (self::$createMethodExists) {
            Notification::make()
                ->title(static::$resourceMenuLabel)
                ->body(static::$successCreateNotification)
                ->success()
                ->send();
        }
    }

    public function create(): void
    {
        self::initCreate();

        if (self::$createMethodExists) {
            redirect()->route(static::$baseRouteName . '.index');
        }
    }

    public function createAndStay(): void
    {
        self::initCreate();

        if (self::$createMethodExists) {
            $this->form->fill();
        }
    }

    protected static function store(array $data): void
    {
        self::$createMethodExists = false;

        Notification::make()
            ->body('É necessário criar o método store.')
            ->warning()
            ->send();
    }

}
