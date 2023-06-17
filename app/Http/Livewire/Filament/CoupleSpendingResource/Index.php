<?php

namespace App\Http\Livewire\Filament\CoupleSpendingResource;

use App\Actions\Couple;
use App\Http\Livewire\Components\FilamentModals;
use App\Models\CoupleSpending;
use Exception;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends FilamentModals
{

    use AuthorizesRequests;

    protected static ?string $model = CoupleSpending::class;

    protected static ?string $resourcePluralName = 'Gastos';

    protected static ?string $resourceName = 'gasto';

    protected static ?string $baseRouteName = 'couple.spending';

    public function render(): View
    {
        $this->authorize('viewAny', [CoupleSpending::class]);

        return view('livewire.filament.spending-resource.index');
    }

    /** @throws Exception */
    protected function getFormSchema(): array
    {
        return Couple\Spending\MakeFormSchema::execute();
    }

    protected function getTableQuery(): Builder|Relation
    {
        return CoupleSpending::query()
            ->where('user_id', auth()->id());
    }

    protected function getTableColumns(): array
    {
        return Couple\Spending\MakeTableColumns::execute(
            closureTooltip: fn(TextColumn $column): ?string => $this->closureTooltip($column),
        );
    }

    public static function beforeCreate(array $state): array
    {
        $state['user_id'] = auth()->id();

        return $state;
    }

}
