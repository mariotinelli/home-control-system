<?php

namespace App\Http\Livewire\Filament\MarketItemCategoryResource;

use App\Actions\Markets;
use App\Http\Livewire\Components\FilamentModals;
use App\Models\MarketItemCategory;
use Exception;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends FilamentModals
{
    use AuthorizesRequests;

    protected static ?string $model = MarketItemCategory::class;

    protected static ?string $resourcePluralName = 'Categorias';

    protected static ?string $resourceName = 'categoria';

    protected static ?string $baseRouteName = 'markets.items.categories';

    public function render(): View
    {
        $this->authorize('viewAny', [MarketItemCategory::class]);

        return view('livewire.filament.market-item-category-resource.index');
    }

    /** @throws Exception */
    protected function getFormSchema(): array
    {
        return Markets\Items\Categories\MakeFormSchema::execute();
    }

    protected function getTableQuery(): Builder|Relation
    {
        return MarketItemCategory::query()
            ->whereUserId(auth()->id());
    }

    protected function getTableColumns(): array
    {
        return Markets\Items\Categories\MakeTableColumns::execute(
            closureTooltip: fn (TextColumn $column): ?string => $this->closureTooltip($column),
        );
    }

    public static function beforeCreate(array $state): array
    {
        $state['user_id'] = auth()->id();

        return $state;
    }

}
