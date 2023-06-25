<?php

namespace App\Http\Livewire\Filament\CreditCardResource;

use App\Actions\Banks;
use App\Http\Livewire\Components\FilamentPages;
use App\Models\CreditCard;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends FilamentPages
{
    use AuthorizesRequests;

    protected static ?string $model = CreditCard::class;

    protected static ?string $resourcePluralName = 'Cartões de Crédito';

    protected static ?string $resourceName = 'cartão de crédito';

    protected static ?string $baseRouteName = 'banks.credit-cards';

    public function render(): View
    {
        $this->authorize('viewAny', [CreditCard::class]);

        return view('livewire.filament.credit-card-resource.index');
    }

    protected function getTableQuery(): Builder|Relation
    {
        return CreditCard::query()
            ->whereUserId(auth()->id());
    }

    protected function getTableColumns(): array
    {
        return Banks\CreditCards\MakeTableColumns::execute(
            closureTooltip: fn (TextColumn $column): ?string => $this->closureTooltip($column),
        );
    }

    public static function beforeDelete(Model $record): void
    {
        /** @var CreditCard $record */
        $record->spendings()->delete();
    }

}
