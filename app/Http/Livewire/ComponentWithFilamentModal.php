<?php

namespace App\Http\Livewire;

use App\Traits\{HasFilamentModalForms, HasFilamentModalTables};
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * @property Forms\ComponentContainer|View|mixed|null $form
 */
class ComponentWithFilamentModal extends Component implements HasForms, HasTable
{
    use HasFilamentModalForms;
    use HasFilamentModalTables;

    protected static ?string $resourceMenuLabel = null;

    protected static ?string $resourceLabel = null;

    protected static ?string $createActionColor = null;

    protected static string $actionsColumnLabel = 'Ações';

    protected static string $successCreateNotification = 'Cadastro realizada com sucesso';

    protected static string $successUpdateNotification = 'Atualização realizada com sucesso';

    protected static string $successDeleteNotification = 'Deleção realizada com sucesso';

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
