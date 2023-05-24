<?php

namespace App\Http\Livewire\Couple\Spending;

use App\Http\Livewire\ComponentWithFilamentModal;
use App\Models\{CoupleSpending};
use App\Rules\CoupleSpendingCategoryOwnerRule;
use Filament\Forms\Components\{Grid, Select, TextInput};
use Filament\Tables\Columns\{TextColumn};
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Leandrocfe\FilamentPtbrFormFields\PtbrMoney;

class Index extends ComponentWithFilamentModal
{
    use AuthorizesRequests;

    protected static ?string $model = CoupleSpending::class;

    protected static ?string $resourceMenuLabel = 'Gastos';

    protected static ?string $resourceLabel = 'gasto';

    protected static ?string $createActionColor = 'success';

    public function render(): View
    {
        $this->authorize('viewAny', [CoupleSpending::class]);

        return view('livewire.couple.spending.index');
    }

    protected static function create(array $data): void
    {
        auth()
            ->user()
            ->coupleSpendingCategories()
            ->create($data);
    }

    protected function getFormSchema(): array
    {
        return [

            Grid::make()
                ->schema([
                    Select::make('category')
                        ->label('Categoria')
                        ->preload()
                        ->relationship('category', 'name', function (Builder $query): void {
                            $query->where('user_id', auth()->id());
                        })
                        ->required()
                        ->exists('couple_spending_categories', 'id')
                        ->rule(new CoupleSpendingCategoryOwnerRule())
                        ->columnSpan(2),

                    TextInput::make('description')
                        ->label('Descrição')
                        ->required()
                        ->string()
                        ->minLength(3)
                        ->maxLength(255)
                        ->columnSpan(2),

                    PtbrMoney::make('amount')
                        ->label('Valor')
                        ->required(), // Max value for MySQL DECIMAL(10,2)

                    TextInput::make('date'),
                ]),
        ];
    }

    protected function getTableQuery(): Builder|Relation
    {
        return CoupleSpending::query()
            ->where('user_id', auth()->id());
    }

    protected function getTableColumns(): array
    {
        return [

            TextColumn::make('id')
                ->label('ID')
                ->sortable()
                ->searchable(),

            TextColumn::make('category.name')
                ->label('Categoria')
                ->sortable()
                ->searchable(),

            TextColumn::make('description')
                ->label('Descrição')
                ->sortable()
                ->searchable(),

            TextColumn::make('amount')
                ->label('Valor')
                ->money('BRL')
                ->sortable()
                ->searchable(),

            TextColumn::make('date')
                ->label('Data')
                ->date('d/m/Y')
                ->sortable()
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query
                        ->whereRaw("DATE_FORMAT(date, '%d/%m/%Y') LIKE ?", ["%$search%"]);
                }),

        ];
    }
}
