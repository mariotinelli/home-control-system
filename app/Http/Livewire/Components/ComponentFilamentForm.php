<?php

namespace App\Http\Livewire\Components;

use App\Traits\{HasFilamentForms};
use Filament\Forms;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

/**
 * @property Forms\ComponentContainer|View|mixed|null $form
 */
class ComponentFilamentForm extends Component implements Forms\Contracts\HasForms
{
    use HasFilamentForms;

    protected static ?string $model = null;

    protected static ?string $resourcePluralName = null;

    protected static ?string $resourceName = null;

    protected static string $submitButtonColor = 'success';

    protected static ?string $baseRouteName = null;

    protected static ?string $notificationTitle = null;

    protected static ?string $notificationBody = null;

    protected static bool $sendNotification = true;

    protected function getFormModel(): Model|string|null
    {
        return $this->record ?? static::$model;
    }
}
