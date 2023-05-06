<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers\RolesRelationManager;
use App\Models\Permission;
use Filament\Resources\{Form, Resource, Table};
use Filament\{Forms, Tables};

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $modelLabel = 'permissão';

    protected static ?string $pluralModelLabel = 'permissões';

    protected static ?string $navigationIcon = 'heroicon-o-exclamation';

    protected static ?string $navigationGroup = 'Gerenciamento de Acesso';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Grid::make(1)
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->label('Nome')
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('guard_name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RolesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit'   => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
