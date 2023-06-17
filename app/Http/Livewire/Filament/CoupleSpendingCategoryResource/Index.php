<?php

namespace App\Http\Livewire\Filament\CoupleSpendingCategoryResource;

use App\Actions\Couple;
use App\Http\Livewire\Components\FilamentModals;
use App\Models\CoupleSpendingCategory;
use Exception;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends FilamentModals
{

    use AuthorizesRequests;

    protected static ?string $model = CoupleSpendingCategory::class;

    protected static ?string $resourcePluralName = 'Categorias de Gastos';

    protected static ?string $resourceName = 'categoria de gasto';

    protected static ?string $baseRouteName = 'couple.spending.categories';

    public function render(): View
    {
        $this->authorize('viewAny', [CoupleSpendingCategory::class]);

        return view('livewire.filament.couple-spending-category-resource.index');
    }

    protected function getTableQuery(): Builder|Relation
    {
        return CoupleSpendingCategory::query()
            ->where('user_id', auth()->id());
    }

    /** @throws Exception */
    protected function getFormSchema(): array
    {
        return Couple\Spending\Categories\MakeFormSchema::execute();
    }

    protected function getTableColumns(): array
    {
        return Couple\Spending\Categories\MakeTableColumns::execute(
            closureTooltip: fn(TextColumn $column): ?string => $this->closureTooltip($column),
        );
    }

    public static function beforeCreate(array $state): array
    {
        $state['user_id'] = auth()->id();

        return $state;
    }

}
