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

    public ?Model $record = null;

    protected static ?string $model = null;

    protected static ?string $resourceMenuLabel = null;

    protected static ?string $resourceLabel = null;

    protected static ?string $createActionColor = null;

    protected static ?string $baseRouteName = null;

    protected static string $successCreateNotification = 'Cadastro realizado com sucesso';

    protected static string $successUpdateNotification = 'Atualização realizada com sucesso';

    protected function getFormModel(): Model|string|null
    {
        return $this->record;
    }
}
