<?php

namespace App\Http\Livewire\Filament\BankAccountResource;

use App\Actions\Banks;
use App\Http\Livewire\Components\FilamentPages;
use App\Models\BankAccount;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends FilamentPages
{
    use AuthorizesRequests;

    protected static ?string $model = BankAccount::class;

    protected static ?string $resourcePluralName = 'Contas Bancárias';

    protected static ?string $resourceName = 'conta bancária';

    protected static ?string $baseRouteName = 'banks.accounts';

    public function render(): View
    {
        $this->authorize('viewAny', [BankAccount::class]);

        return view('livewire.filament.bank-account-resource.index');
    }

    protected function getTableQuery(): Builder|Relation
    {
        return BankAccount::query()
            ->whereUserId(auth()->id());
    }

    protected function getTableColumns(): array
    {
        return Banks\Accounts\MakeTableColumns::execute(
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
