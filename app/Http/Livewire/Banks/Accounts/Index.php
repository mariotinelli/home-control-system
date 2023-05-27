<?php

namespace App\Http\Livewire\Banks\Accounts;

use App\Actions;
use App\Models\BankAccount;
use App\Traits\HasLimitColumnWithTooltip;
use Exception;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;
use PhpParser\Node\Expr\Closure;

class Index extends Component implements HasTable
{
    use InteractsWithTable;
    use HasLimitColumnWithTooltip;

    protected static string $defaultSortColumn = 'id';

    protected static string $defaultSortDirection = 'desc';

    protected static ?string $model = BankAccount::class;

    protected static ?string $resourceMenuLabel = 'Contas Bancárias';

    protected static ?string $resourceLabel = 'conta bancária';

    protected static ?string $createActionColor = 'success';

    public function render(): View
    {
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

    protected function getDefaultTableSortColumn(): ?string
    {
        return static::$defaultSortColumn;
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return static::$defaultSortDirection;
    }

    protected function getTableHeading(): string|Htmlable|Closure|null
    {
        return view('components.app.filament.resources.table.heading', ['title' => static::$resourceMenuLabel]);
    }

    /** @throws Exception */
    protected function getTableHeaderActions(): array
    {
        return [
            CreateAction::make('create')
                ->url(fn (): string => route('banks.accounts.create'))
                ->tooltip('Criar ' . static::$resourceLabel)
                ->icon('heroicon-s-plus')
                ->label('Criar ' . static::$resourceLabel)
                ->color(static::$createActionColor ?? 'primary')
                ->visible(fn (): bool => auth()->user()->can('create', static::$model)),
        ];
    }
}
