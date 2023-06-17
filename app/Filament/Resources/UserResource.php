<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\{Pages};
use App\Models\User;
use Filament\Resources\{Form, Resource, Table};
use Filament\Tables\Filters\SelectFilter;
use Filament\{Forms, Tables};
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'usuário';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->string()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->unique(ignoreRecord: true)
                    ->string()
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->string()
                    ->minLength(8)
                    ->maxLength(12)
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),

                Forms\Components\Select::make('roles')
                    ->label('Funções')
                    ->required()
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload()
                    ->columnSpan(3),
            ])
            ->columns(3);
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
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('filament_roles')
                    ->label('Funções')
                    ->colors([
                        'danger'    => static fn ($state, User $record): bool => $record->hasRole('Usuário'),
                        'secondary' => static fn ($state, User $record): bool => $record->hasRole('Usuário Prata'),
                        'warning'   => static fn ($state, User $record): bool => $record->hasRole('Usuário Ouro'),
                        'success'   => static fn ($state, User $record): bool => $record->hasRole('Administrador'),
                    ]),

            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('roles')->relationship('roles', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
