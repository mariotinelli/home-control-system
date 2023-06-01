<?php

namespace App\Traits\FIlament\Resources\Page;

use App\Traits\FIlament\Resources\Page\Forms\HasCreateForm;
use App\Traits\FIlament\Resources\Page\Forms\HasEditForm;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

trait HasResourceForm
{
    use InteractsWithForms;
    use AuthorizesRequests;
    use HasCreateForm;
    use HasEditForm;

    public $data;

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    public static function getNotification(Model $record): ?Notification
    {
        return Notification::make()
            ->title(static::$notificationTitle ?: 'Cadastro')
            ->body(static::$notificationBody ?: 'O registro foi cadastrado com sucesso')
            ->success()
            ->send();
    }

    public static function getRedirectUrl(Model $record): ?string
    {
        return null;
    }
}
