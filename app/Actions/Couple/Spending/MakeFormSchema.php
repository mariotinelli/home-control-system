<?php

namespace App\Actions\Couple\Spending;

use App\Actions\Couple;
use App\Rules\CoupleSpendingCategoryOwnerRule;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\{DatePicker, Grid, Select, TextInput};
use Illuminate\Database\Eloquent\Builder;
use Leandrocfe\FilamentPtbrFormFields\PtbrMoney;

class MakeFormSchema
{
    public static function execute(): array
    {
        return [

            Grid::make()
                ->schema([
                    Select::make('couple_spending_category_id')
                        ->label('Categoria')
                        ->preload()
                        ->relationship('category', 'name', function (Builder $query): void {
                            $query->where('user_id', auth()->id());
                        })
                        ->required()
                        ->exists('couple_spending_categories', 'id')
                        ->rule(new CoupleSpendingCategoryOwnerRule())
                        ->columnSpan(2)
                        ->createOptionForm(
                            auth()->user()->can('couple_spending_category_create')
                                ? Couple\Spending\Categories\MakeFormSchema::execute()
                                : null
                        )
                        ->createOptionAction(function (Action $action) {
                            return $action
                                ->action(
                                    fn (array $data) => Couple\Spending\Categories\CreateFromAuthUser::execute($data)
                                )
                                ->modalHeading(
                                    view('components.app.filament.resources.modal.heading', [
                                        'title' => 'Criar categoria',
                                    ])
                                )
                                ->modalButton('Criar')
                                ->color('success')
                                ->tooltip('Criar categoria')
                                ->modalWidth('lg');
                        }),

                    TextInput::make('description')
                        ->label('Descrição')
                        ->required()
                        ->string()
                        ->minLength(3)
                        ->maxLength(255)
                        ->columnSpan(2),

                    PtbrMoney::make('amount')
                        ->label('Valor')
                        ->required(),

                    DatePicker::make('date')
                        ->label('Data')
                        ->placeholder('Selecione uma data')
                        ->required()
                        ->displayFormat('d/m/Y'),
                ]),
        ];
    }

}
