<?php

namespace App\Http\Livewire\Components;

use App\Traits\{FIlament\Resources\Modal\HasFilamentForms, FIlament\Resources\Modal\HasFilamentTable};
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

/**
 * @property Forms\ComponentContainer|View|mixed|null $form
 */
class FilamentModals extends Component implements HasForms, HasTable
{
    use HasFilamentForms;
    use HasFilamentTable;
    use AuthorizesRequests;

    protected static ?string $model = null;

    protected static ?string $resourcePluralName = null;

    protected static ?string $resourceName = null;

    protected static ?string $baseRouteName = null;

    protected static ?string $createNotificationTitle = null;

    protected static ?string $createNotificationBody = null;

    protected static ?string $updateNotificationTitle = null;

    protected static ?string $updateNotificationBody = null;

    protected static bool $sendNotification = true;

    protected static string $createButtonColor = 'success';

    protected static string $updateButtonColor = 'success';

    protected static string $defaultSortColumn = 'id';

    protected static string $defaultSortDirection = 'desc';

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
        'tableColumnSearchQueries',
    ];

    public $data;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }
}
