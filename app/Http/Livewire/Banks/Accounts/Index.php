<?php

namespace App\Http\Livewire\Banks\Accounts;

use App\Actions;
use App\Http\Livewire\Components\FilamentPages;
use App\Models\BankAccount;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\{Builder, Model};

class Index extends FilamentPages
{
    protected static ?string $model = BankAccount::class;

    protected static ?string $baseRouteName = 'banks.accounts';

    protected static ?string $resourcePluralName = 'Contas Bancárias';

    protected static ?string $resourceName = 'conta bancária';

    protected static ?string $createActionColor = 'success';

    protected static string $successDeleteNotification = 'Deleção realizada com sucesso';

    protected static string $defaultSortColumn = 'id';

    protected static string $defaultSortDirection = 'desc';

    public function render(): View
    {
        $this->authorize('viewAny', [BankAccount::class]);

        return view('livewire.banks.accounts.index');
    }

    protected $queryString = [
        'tableFilters',
        'tableSortColumn',
        'tableSortDirection',
        'tableSearchQuery' => ['except' => ''],
        'tableColumnSearchQueries',
    ];

    protected function getTableQuery(): Builder|Relation
    {
        return BankAccount::query()
            ->whereUserId(auth()->id());
    }

    protected function getTableColumns(): array
    {
        return Actions\Banks\Accounts\MakeTableColumns::execute(
            closureTooltip: fn (TextColumn $column): ?string => $this->closureTooltip($column),
        );
    }

    public static function beforeDelete(Model $record): void
    {
        /** @var BankAccount $record */
        $record->entries()->delete();
        $record->withdrawals()->delete();
    }
}
