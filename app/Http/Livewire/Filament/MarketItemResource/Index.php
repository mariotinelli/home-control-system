<?php

namespace App\Http\Livewire\Filament\MarketItemResource;

use App\Actions\Markets;
use App\Http\Livewire\Components\FilamentModals;
use App\Models\MarketItem;
use Exception;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends FilamentModals
{
    use AuthorizesRequests;

    protected static ?string $model = MarketItem::class;

    protected static ?string $resourcePluralName = 'Itens de Mercado';

    protected static ?string $resourceName = 'itens de mercado';

    protected static ?string $baseRouteName = 'markets.items';

    public function render(): View
    {
        $this->authorize('viewAny', [MarketItem::class]);

        return view('livewire.filament.market-item-resource.index');
    }

    /** @throws Exception */
    protected function getFormSchema(): array
    {
        return Markets\Items\MakeFormSchema::execute();
    }

    protected function getTableQuery(): Builder|Relation
    {
        return MarketItem::query()
            ->whereUserId(auth()->id());
    }

    protected function getTableColumns(): array
    {
        return Markets\Items\MakeTableColumns::execute(
            closureTooltip: fn (TextColumn $column): ?string => $this->closureTooltip($column),
        );
    }

    public static function beforeCreate(array $state): array
    {
        $state['user_id'] = auth()->id();

        return $state;
    }

}
