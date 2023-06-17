<?php

use App\Filament;
use App\Models\{Permission, User};
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
    get(Filament\Resources\PermissionResource::getUrl('index'))
        ->assertSuccessful();
});

it('user without access admin permission can access the page', function () {
    // Arrange
    $nonAdmin = User::factory()->create();

    // Act
    actingAs($nonAdmin);

    get(Filament\Resources\PermissionResource::getUrl('index'))
        ->assertForbidden();
});

it('can list permissions', function () {
    // Arrange
    $permissions = Permission::factory()->count(2)->create();

    // Act
    livewire(Filament\Resources\PermissionResource\Pages\ListPermissions::class)
        ->assertCanSeeTableRecords($permissions)
        ->assertCountTableRecords(Permission::query()->count());
});

it('can render permission ids', function () {
    User::factory()->count(1)->create();

    livewire(Filament\Resources\PermissionResource\Pages\ListPermissions::class)
        ->assertCanRenderTableColumn('id');
});

it('can render permission names', function () {
    User::factory()->count(1)->create();

    livewire(Filament\Resources\PermissionResource\Pages\ListPermissions::class)
        ->assertCanRenderTableColumn('name');
});

it('can delete permissions', function () {
    // Arrange
    $permission = Permission::factory()->create();

    // Act
    livewire(Filament\Resources\PermissionResource\Pages\ListPermissions::class)
        ->callTableAction(DeleteAction::class, $permission);

    // Assert
    assertModelMissing($permission);
});

it('can bulk delete permissions', function () {
    // Arrange
    $permissions = Permission::factory()->count(10)->create();

    // Act
    livewire(Filament\Resources\PermissionResource\Pages\ListPermissions::class)
        ->callTableBulkAction(DeleteBulkAction::class, $permissions);

    foreach ($permissions as $permission) {
        assertModelMissing($permission);
    }
});
