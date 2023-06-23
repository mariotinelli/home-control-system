<?php

namespace App\Http\Livewire\Filament\MarketResource;

use App\Actions\Markets;
use App\Http\Livewire\Components\FilamentModals;
use App\Models\Market;
use Exception;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends FilamentModals
{
    use AuthorizesRequests;

    protected static ?string $model = Market::class;

    protected static ?string $resourcePluralName = 'Mercados';

    protected static ?string $resourceName = 'mercado';

    protected static ?string $baseRouteName = 'markets';

    public function render(): View
    {
        $this->authorize('viewAny', [Market::class]);

        return view('livewire.filament.market-resource.index');
    }

    /** @throws Exception */
    protected function getFormSchema(): array
    {
        return Markets\Markets\MakeFormSchema::execute();
    }

    protected function getTableQuery(): Builder|Relation
    {
        return Market::query()
            ->whereUserId(auth()->id());
    }

    protected function getTableColumns(): array
    {
        return Markets\Markets\MakeTableColumns::execute(
            closureTooltip: fn (TextColumn $column): ?string => $this->closureTooltip($column),
        );
    }

    public static function beforeCreate(array $state): array
    {
        $state['user_id'] = auth()->id();

        return $state;
    }

}
