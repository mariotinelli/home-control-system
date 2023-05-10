<?php

use App\Filament\Resources;
use App\Models\{Permission, Role};
use Filament\Tables\Actions\{AttachAction,
    CreateAction,
    DeleteAction,
    DeleteBulkAction,
    DetachAction,
    DetachBulkAction,
    EditAction};

use function Pest\Laravel\{assertDatabaseHas, assertModelMissing};
use function Pest\Livewire\livewire;

it('can render relation manager', function () {
    $role = Role::factory()
        ->has(Permission::factory()->count(10))
        ->create();

    livewire(Resources\RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $role,
    ])
        ->assertSuccessful();
});

it('can list permissions', function () {
    $role = Role::factory()
        ->has(Permission::factory()->count(2))
        ->create();

    livewire(Resources\RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $role,
    ])
        ->assertCanSeeTableRecords($role->permissions);
});

it('can render permission ids', function () {
    // Arrange
    $role = Role::factory()
        ->has(Permission::factory()->count(1))
        ->create();

    // Act
    livewire(Resources\RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $role,
    ])
        ->assertCanRenderTableColumn('id');
});

it('can render permission names', function () {
    // Arrange
    $role = Role::factory()
        ->has(Permission::factory()->count(1))
        ->create();

    // Act
    livewire(Resources\RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $role,
    ])
        ->assertCanRenderTableColumn('name');
});

it('can create a new permission', function () {
    $role = Role::factory()
        ->has(Permission::factory()->count(1))
        ->create();

    $newData = Permission::factory()->make();

    livewire(Resources\RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $role,
    ])
        ->callTableAction(CreateAction::class, data: [
            'name' => $newData->name,
        ])
        ->assertHasNoTableActionErrors();

    // Assert
    assertDatabaseHas(Permission::class, [
        'name' => $newData->name,
    ]);
});

it('can attach permissions', function () {
    // Arrange
    $role = Role::factory()
        ->has(Permission::factory()->count(1))
        ->create();

    $permission = Permission::factory()->create();

    // Act
    livewire(Resources\RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $role,
    ])
        ->callTableAction(AttachAction::class, data: [
            'recordId' => $permission->id,
        ])
        ->assertHasNoTableActionErrors();

    // Assert
    expect($role->refresh()->permissions->count())
        ->toBe(2);

});

it('can detach permissions', function () {
    // Arrange
    $role = Role::factory()
        ->has(Permission::factory()->count(1))
        ->create();

    $permission = $role->permissions->first();

    // Act
    livewire(Resources\RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $role,
    ])
        ->callTableAction(DetachAction::class, $permission)
        ->assertHasNoTableActionErrors();

    // Assert
    expect($role->refresh()->permissions->count())
        ->toBe(0);

});

it('can edit permissions', function () {
    $role = Role::factory()
        ->has(Permission::factory()->count(1))
        ->create();

    $permission = $role->permissions->first();

    livewire(Resources\RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $role,
    ])
        ->callTableAction(EditAction::class, $permission, data: [
            'name' => $name = fake()->words(asText: true),
        ])
        ->assertHasNoTableActionErrors();

    expect($permission->refresh())
        ->name->toBe($name);
});

it('can delete permissions', function () {
    $role = Role::factory()
        ->has(Permission::factory()->count(1))
        ->create();

    $permission = $role->permissions->first();

    livewire(Resources\RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $role,
    ])
    ->callTableAction(DeleteAction::class, $permission);

    assertModelMissing($permission);
});

it('can bulk delete permissions', function () {
    // Arrange
    $role = Role::factory()
        ->has(Permission::factory()->count(5))
        ->create();

    $permissions = $role->permissions;

    // Act
    livewire(Resources\RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $role,
    ])
        ->callTableBulkAction(DeleteBulkAction::class, $permissions);

    // Assert
    foreach ($permissions as $permission) {
        assertModelMissing($permission);
    }
});

it('can bulk detach permissions', function () {
    // Arrange
    $role = Role::factory()
        ->has(Permission::factory()->count(3))
        ->create();

    $permissions = $role->permissions;

    // Act
    livewire(Resources\RoleResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $role,
    ])
        ->callTableBulkAction(DetachBulkAction::class, $permissions)
        ->assertHasNoTableActionErrors();

    // Assert
    expect($role->refresh()->permissions->count())
        ->toBe(0);

});
