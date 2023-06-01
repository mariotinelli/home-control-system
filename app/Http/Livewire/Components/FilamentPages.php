<?php

namespace App\Http\Livewire\Components;

use App\Traits\{FIlament\Resources\Page\HasResourceTable};
use Filament\Forms;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\View\View;
use Livewire\Component;

/**
 * @property Forms\ComponentContainer|View|mixed|null $form
 */
class FilamentPages extends Component implements HasTable
{
    use HasResourceTable;

    protected static ?string $model = null;

    protected static ?string $resourceMenuLabel = null;

    protected static ?string $resourceLabel = null;

    protected static ?string $createActionColor = null;

    protected static ?string $baseRouteName = null;

    protected static string $actionsColumnLabel = 'Ações';

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
}
