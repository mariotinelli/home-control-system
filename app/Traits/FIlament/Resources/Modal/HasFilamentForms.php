<?php

namespace App\Traits\FIlament\Resources\Modal;

use App\Traits\FIlament\Resources\Modal\Forms\HasCreateForm;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

trait HasFilamentForms
{
    use InteractsWithForms;
    use HasCreateForm;

    public static function getCreateNotification(Model $record): ?Notification
    {
        return Notification::make()
            ->title(static::$createNotificationTitle ?: 'Cadastro')
            ->body(static::$createNotificationBody ?: 'O registro foi cadastrado com sucesso')
            ->success()
            ->send();
    }
}
