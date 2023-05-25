<?php

namespace App\Filament\Resources\RoleResource\RelationManagers;

use App\Models\{Permission, Role};
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\{Form, Table};
use Filament\{Forms, Tables};

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'permissão';

    protected static ?string $pluralModelLabel = 'permissões';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
            ])
            ->columns(1);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
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
                    ->preloadRecordSelect()
                    ->after(function (Tables\Actions\AttachAction $action, RelationManager $livewire) {

                        /** @var Role $ownerRecord */
                        $ownerRecord = $livewire->ownerRecord;
                        $ownerRecord->givePermissionTo(Permission::find($action->getFormData()['recordId']));

                    }),
            ])
            ->actions([

                Tables\Actions\EditAction::make(),

                Tables\Actions\DetachAction::make()
                    ->after(function (Tables\Actions\DetachAction $action, RelationManager $livewire) {

                        /** @var Role $ownerRecord */
                        $ownerRecord = $livewire->ownerRecord;

                        /** @var Permission $record */
                        $record = $action->getRecord();

                        $ownerRecord->revokePermissionTo($record->name);
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([

                Tables\Actions\DetachBulkAction::make()
                    ->after(function (Tables\Actions\DetachBulkAction $action, RelationManager $livewire) {

                        /** @var Role $ownerRecord */
                        $ownerRecord = $livewire->ownerRecord;

                        foreach ($action->getRecords() as $record) {
                            $ownerRecord->revokePermissionTo($record->name);
                        }
                    }),

                Tables\Actions\DeleteBulkAction::make(),

            ]);
    }
}
