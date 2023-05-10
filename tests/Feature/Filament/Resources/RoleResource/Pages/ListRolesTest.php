<?php

use App\Filament;
use App\Models\{Role, User};
use Filament\Tables\Actions\{DeleteAction, DeleteBulkAction};

use function Pest\Laravel\{actingAs, assertModelMissing, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->admin = User::factory()->create();

    $this->admin->givePermissionTo(getAdminPermissions());

    actingAs($this->admin);

});

it('user with access admin permission can access the page', function () {
    get(Filament\Resources\RoleResource::getUrl('index'))
        ->assertSuccessful();
});

it('user without access admin permission can access the page', function () {
    // Arrange
    $nonAdmin = User::factory()->create();

    // Act
    actingAs($nonAdmin);

    get(Filament\Resources\RoleResource::getUrl('index'))
        ->assertForbidden();
});

it('can list roles', function () {
    // Arrange
    $roles = Role::factory()->count(2)->create();

    // Act
    livewire(Filament\Resources\RoleResource\Pages\ListRoles::class)
        ->assertCanSeeTableRecords($roles)
        ->assertCountTableRecords(Role::query()->count());
});

it('can render role ids', function () {
    User::factory()->count(1)->create();

    livewire(Filament\Resources\RoleResource\Pages\ListRoles::class)
        ->assertCanRenderTableColumn('id');
});

it('can render role names', function () {
    User::factory()->count(1)->create();

    livewire(Filament\Resources\RoleResource\Pages\ListRoles::class)
        ->assertCanRenderTableColumn('name');
});

it('can delete roles', function () {
    // Arrange
    $role = Role::factory()->create();

    // Act
    livewire(Filament\Resources\RoleResource\Pages\ListRoles::class)
        ->callTableAction(DeleteAction::class, $role);

    // Assert
    assertModelMissing($role);
});

it('can bulk delete posts', function () {
    // Arrange
    $roles = Role::factory()->count(10)->create();

    // Act
    livewire(Filament\Resources\RoleResource\Pages\ListRoles::class)
        ->callTableBulkAction(DeleteBulkAction::class, $roles);

    foreach ($roles as $role) {
        assertModelMissing($role);
    }
});
