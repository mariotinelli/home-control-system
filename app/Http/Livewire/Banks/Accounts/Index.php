<?php

namespace App\Http\Livewire\Banks\Accounts;

use App\Actions;
use App\Models\BankAccount;
use App\Traits\HasLimitColumnWithTooltip;
use Exception;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use PhpParser\Node\Expr\Closure;

class Index extends Component implements HasTable
{
    use InteractsWithTable;
    use HasLimitColumnWithTooltip;
    use AuthorizesRequests;

    protected static string $defaultSortColumn = 'id';

    protected static string $defaultSortDirection = 'desc';

    protected static ?string $model = BankAccount::class;

    protected static ?string $resourceMenuLabel = 'Contas Bancárias';

    protected static ?string $resourceLabel = 'conta bancária';

    protected static ?string $createActionColor = 'success';

    protected static string $successDeleteNotification = 'Deleção realizada com sucesso';

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

    /** @throws Exception */
    protected function getTableActions(): array
    {
        return [

            Tables\Actions\EditAction::make()
                ->disabled(fn (Model $record): bool => !auth()->user()->can('update', $record))
                ->button()
                ->tooltip('Editar ' . static::$resourceLabel)
                ->icon(fn ($action) => $action->isDisabled() ? 'heroicon-s-lock-closed' : 'heroicon-s-pencil-alt')
                ->url(fn (Model $record): string => route('banks.accounts.edit', $record)),

            Tables\Actions\DeleteAction::make()
                ->disabled(fn (Model $record): bool => !auth()->user()->can('delete', $record))
                ->button()
                ->tooltip(function ($action) {
                    if ($action->isDisabled()) {
                        $action->icon('heroicon-s-lock-closed');
                    }

                    return 'Deletar ' . static::$resourceLabel;
                })
                ->modalHeading('Deletar ' . static::$resourceLabel)
                ->successNotification(
                    Notification::make()
                        ->title(static::$resourceMenuLabel)
                        ->body(static::$successDeleteNotification)
                        ->success()
                ),

        ];
    }

    protected function getTableRecordUrlUsing(): \Closure
    {
        return fn (Model $record): string => route('banks.accounts.edit', $record);
    }
}
