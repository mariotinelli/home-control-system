<?php

namespace App\Http\Livewire\Couple\Spending;

use App\Actions\Couple;
use App\Actions\Filament\TableActions;
use App\Models\{CoupleSpending, User};
use App\Traits\HasLimitColumnWithTooltip;
use Closure;
use Exception;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\{HasMany, Relation};
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component implements HasTable
{
    use AuthorizesRequests;
    use InteractsWithTable;
    use HasLimitColumnWithTooltip;

    public User $user;

    public function mount(): void
    {
        $this->user = auth()->user();
    }

    public function render(): View
    {
        $this->authorize('couple_spending_read');

        return view('livewire.couple.spending.index');
    }

    public function getPlaceWithMoreSpendingProperty(): Model|HasMany|null
    {
        return $this->user->coupleSpendings()
            ->with(['place'])
            ->select([
                'couple_spending_place_id',
                \DB::raw('SUM(amount) as total'),
            ])
            ->groupBy('couple_spending_place_id')
            ->orderBy('total', 'desc')
            ->first();
    }

    public function getCategoryWithMoreSpendingProperty(): Model|HasMany|null
    {
        return $this->user->coupleSpendings()
            ->with(['category'])
            ->select([
                'couple_spending_category_id',
                \DB::raw('SUM(amount) as total'),
            ])
            ->groupBy('couple_spending_category_id')
            ->orderBy('total', 'desc')
            ->first();
    }

    protected function getTableHeading(): string|Htmlable|Closure|null
    {
        return view('components.app.filament.resources.table.heading', [
            'title' => 'Últimos Gastos',
        ]);
    }

    protected function getTableQuery(): Builder|Relation
    {
        return CoupleSpending::with(['category', 'place'])
            ->where('user_id', auth()->id());
    }

    protected function getTableColumns(): array
    {
        return Couple\Spending\MakeTableColumns::execute(
            closureTooltip: fn (TextColumn $column): ?string => $this->closureTooltip($column),
        );
    }

    /**
     * @throws Exception
     */
    protected function getTableActions(): array
    {
        return [
            TableActions\MakeEditAction::execute(
                form: Couple\Spending\MakeFormSchema::execute(),
                resourceName: 'gasto'
            ),
            TableActions\MakeDeleteAction::execute(
                resourceName: 'gasto'
            ),
        ];
    }

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'date';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
}
