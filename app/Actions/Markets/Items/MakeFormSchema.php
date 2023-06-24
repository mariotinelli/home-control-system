<?php

namespace App\Actions\Markets\Items;

use App\Actions\Markets;
use App\Enums\TypeOfWeightEnum;
use App\Rules\MarketItemCategoryOwnerRule;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\{Grid, Select, TextInput};
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Validation\Rules\Unique;
use Leandrocfe\FilamentPtbrFormFields\PtbrMoney;

/** @property Model $record */
class MakeFormSchema
{
    public static function execute(
        Model $record = null,
    ): array {
        return [

            Grid::make()
                ->schema([

                    TextInput::make('name')
                        ->label('Nome')
                        ->placeholder('Nome do Item')
                        ->autofocus()
                        ->required()
                        ->string()
                        ->unique(
                            callback: function (Unique $rule) {
                                return $rule->where('user_id', auth()->id());
                            },
                            ignoreRecord: true,
                        )
                        ->minLength(3)
                        ->maxLength(150),

                    Select::make('market_item_category_id')
                        ->label('Categoria')
                        ->preload()
                        ->relationship('category', 'name', function (Builder $query): void {
                            $query->where('user_id', auth()->id());
                        })
                        ->required()
                        ->exists('market_item_categories', 'id')
                        ->rule(new MarketItemCategoryOwnerRule())
                        ->createOptionForm(
                            auth()->user()->can('market_item_category_create')
                                ? Markets\Items\Categories\MakeFormSchema::execute()
                                : null
                        )
                        ->createOptionAction(function (Action $action) {
                            return $action
                                ->action(
                                    fn (array $data) => Markets\Items\Categories\CreateFromAuthUser::execute($data)
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

                    Select::make('type_weight')
                        ->label('Tipo de peso')
                        ->placeholder('Selecione o tipo de peso')
                        ->string()
                        ->required()
                        ->in(TypeOfWeightEnum::getValues())
                        ->enum(TypeOfWeightEnum::class)
                        ->options(TypeOfWeightEnum::toArray()),

                    PtbrMoney::make('weight')
                        ->label('Peso')
                        ->placeholder('Peso do item')
                        ->prefix(null)
                        ->required(),

                ]),
        ];
    }

}
