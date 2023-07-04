<?php

namespace App\Filament\Resources\PermissionResource\RelationManagers;

use App\Models\Role;
use Exception;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\{Form, Table};
use Filament\{Forms, Tables};

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'função';

    protected static ?string $pluralModelLabel = 'funções';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
            ])->columns(1);
    }

    /** @throws Exception */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->headerActions([

                Tables\Actions\CreateAction::make(),

                Tables\Actions\AttachAction::make()
                    ->after(function (Tables\Actions\AttachAction $action, RelationManager $livewire) {
                        Role::find($action->getFormData()['recordId'])
                            ->givePermissionTo($livewire->ownerRecord);
                    }),

            ])
            ->actions([

                Tables\Actions\EditAction::make(),

                Tables\Actions\DetachAction::make()
                    ->after(function (Tables\Actions\DetachAction $action, RelationManager $livewire) {
                        /** @var Role $record */
                        $record = $action->getRecord();
                        $record->revokePermissionTo($livewire->ownerRecord);
                    }),

                Tables\Actions\DeleteAction::make(),

            ])
            ->bulkActions([

                Tables\Actions\DetachBulkAction::make()
                    ->after(function (Tables\Actions\DetachBulkAction $action, RelationManager $livewire) {
                        foreach ($action->getRecords() as $record) {
                            /** @var Role $record */
                            $record->revokePermissionTo($livewire->ownerRecord);
                        }
                    }),

                Tables\Actions\DeleteBulkAction::make(),

            ]);
    }
}
