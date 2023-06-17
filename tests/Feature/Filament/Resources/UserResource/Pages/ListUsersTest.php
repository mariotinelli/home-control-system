<?php

use App\Filament;
use App\Models\{Role, User};
use Filament\Tables\Actions\{DeleteAction, DeleteBulkAction};

use function Pest\Laravel\{actingAs, assertModelMissing, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->admin = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->admin->givePermissionTo(getManagerPermissions());

    actingAs($this->admin);

});

it('user with access admin permission can access the page', function () {
    get(Filament\Resources\UserResource::getUrl('index'))
        ->assertSuccessful();
});

it('user without access admin permission can access the page', function () {
    // Arrange
    $nonAdmin = User::factory()->create();

    // Act
    actingAs($nonAdmin);

    get(Filament\Resources\UserResource::getUrl('index'))
        ->assertForbidden();
});

it('can list users', function () {
    // Arrange
    $users = User::factory()->count(10)->create();

    // Act
    livewire(Filament\Resources\UserResource\Pages\ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->assertCountTableRecords(User::query()->count());
});

it('can render user ids', function () {
    User::factory()->count(1)->create();

    livewire(Filament\Resources\UserResource\Pages\ListUsers::class)
        ->assertCanRenderTableColumn('id');
});

it('can render user names', function () {
    User::factory()->count(1)->create();

    livewire(Filament\Resources\UserResource\Pages\ListUsers::class)
        ->assertCanRenderTableColumn('name');
});

it('can render user emails', function () {
    User::factory()->count(1)->create();

    livewire(Filament\Resources\UserResource\Pages\ListUsers::class)
        ->assertCanRenderTableColumn('email');
});

it('can render user roles', function () {
    User::factory()->count(1)->create();

    livewire(Filament\Resources\UserResource\Pages\ListUsers::class)
        ->assertCanRenderTableColumn('filament_roles');
});

it('can filter users by `roles`', function () {
    // Arrange
    $users = User::factory()->count(10)->create();

    $users->each(fn ($user) => $user->roles()->attach(Role::all()->random(1)));

    $role = Role::whereName('Administrador')->first();

    // Act
    livewire(Filament\Resources\UserResource\Pages\ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->filterTable('roles', $role->id)
        ->assertCanSeeTableRecords($users->filter(fn ($user) => $user->roles()->where('name', '=', $role->name)->exists()))
        ->assertCanNotSeeTableRecords($users->filter(fn ($user) => $user->roles()->where('name', '!=', $role->name)->exists()))
        ->removeTableFilter('roles')
        ->assertCanSeeTableRecords($users);
});

it('can delete users', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    livewire(Filament\Resources\UserResource\Pages\ListUsers::class)
        ->callTableAction(DeleteAction::class, $user);

    // Assert
    assertModelMissing($user);
});

it('can bulk delete posts', function () {
    // Arrange
    $users = User::factory()->count(10)->create();

    // Act
    livewire(Filament\Resources\UserResource\Pages\ListUsers::class)
        ->callTableBulkAction(DeleteBulkAction::class, $users);

    foreach ($users as $user) {
        assertModelMissing($user);
    }
});
