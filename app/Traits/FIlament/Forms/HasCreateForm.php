<?php

namespace App\Traits\FIlament\Forms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Livewire\Redirector;

trait HasCreateForm
{
    public function store(): Redirector|RedirectResponse
    {
        $record = $this->storeSteps();

        $redirectUrl = static::getRedirectUrl(
            record: $record
        );

        if (!blank($redirectUrl)) {
            return redirect()->to($redirectUrl);
        }

        return redirect()->route(static::$baseRouteName . '.index');
    }

    public function storeAndStay(): void
    {
        $this->storeSteps();

        $this->form->fill();
    }

    private function storeSteps(): Model
    {
        $this->authorize('create', [static::$model]);

        $state = $this->form->getState();

        $state = static::beforeCreate(
            state: $state
        );

        $record = static::$model::create($state);

        $record = static::afterCreate(
            record: $record
        );

        if (static::$sendNotification) {
            static::getNotification(
                record: $record
            );
        }

        return $record;
    }

    public static function beforeCreate(array $state): array
    {
        return $state;
    }

    public static function afterCreate(Model $record): Model
    {
        return $record;
    }

}
