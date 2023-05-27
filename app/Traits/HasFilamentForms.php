<?php

namespace App\Traits;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

trait HasFilamentForms
{
    use InteractsWithForms;
    use AuthorizesRequests;

    private static bool $createMethodExists = true;

    public $data;

    private function initCreate(): void
    {
        $this->authorize('create', [static::$model]);

        $state = $this->form->getState();

        static::create($state);

        if (self::$createMethodExists) {
            Notification::make()
                ->title(static::$resourceMenuLabel)
                ->body(static::$successCreateNotification)
                ->success()
                ->send();
        }
    }

    public function save(): void
    {
        self::initCreate();

        if (self::$createMethodExists) {
            redirect()->route(static::$baseRouteName . '.index');
        }
    }

    public function saveAndStay(): void
    {
        self::initCreate();

        if (self::$createMethodExists) {
            $this->form->fill();
        }
    }

    protected static function create(array $data): void
    {
        self::$createMethodExists = false;

        Notification::make()
            ->body('É necessário criar o método create.')
            ->warning()
            ->send();
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }
}
