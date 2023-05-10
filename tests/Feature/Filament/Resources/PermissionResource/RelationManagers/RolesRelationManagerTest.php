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
    // Arrange
    $permission = Permission::factory()
        ->has(Role::factory()->count(10))
        ->create();

    // Act
    livewire(Resources\PermissionResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $permission,
    ])
        ->assertSuccessful();
});

it('can list roles', function () {
    // Arrange
    $permission = Permission::factory()
        ->has(Role::factory()->count(2))
        ->create();

    // Act
    livewire(Resources\PermissionResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $permission,
    ])
        ->assertCanSeeTableRecords($permission->roles);
});

it('can render permission ids', function () {
    // Arrange
    $permission = Permission::factory()
        ->has(Role::factory()->count(1))
        ->create();

    // Act
    livewire(Resources\PermissionResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $permission,
    ])
        ->assertCanRenderTableColumn('id');
});

it('can render permission names', function () {
    // Arrange
    $permission = Permission::factory()
        ->has(Role::factory()->count(1))
        ->create();

    // Act
    livewire(Resources\PermissionResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $permission,
    ])
        ->assertCanRenderTableColumn('name');
});

it('can create a new role', function () {
    $permission = Permission::factory()
        ->has(Role::factory()->count(1))
        ->create();

    $newData = Role::factory()->make();

    livewire(Resources\PermissionResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $permission,
    ])
        ->callTableAction(CreateAction::class, data: [
            'name' => $newData->name,
        ])
        ->assertHasNoTableActionErrors();

    // Assert
    assertDatabaseHas(Role::class, [
        'name' => $newData->name,
    ]);
});

it('can attach roles', function () {
    // Arrange
    $permission = Permission::factory()
        ->has(Role::factory()->count(1))
        ->create();

    $role = Role::factory()->create();

    // Act
    livewire(Resources\PermissionResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $permission,
    ])
        ->callTableAction(AttachAction::class, data: [
            'recordId' => $role->id,
        ])
        ->assertHasNoTableActionErrors();

    // Assert
    expect($permission->refresh()->roles->count())
        ->toBe(2);

});

it('can detach roles', function () {
    // Arrange
    $permission = Permission::factory()
        ->has(Role::factory()->count(1))
        ->create();

    $role = $permission->roles->first();

    // Act
    livewire(Resources\PermissionResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $permission,
    ])
        ->callTableAction(DetachAction::class, $role)
        ->assertHasNoTableActionErrors();

    // Assert
    expect($permission->refresh()->roles->count())
        ->toBe(0);

});

it('can edit roles', function () {
    $permission = Permission::factory()
        ->has(Role::factory()->count(1))
        ->create();

    $role = $permission->roles->first();

    livewire(Resources\PermissionResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $permission,
    ])
        ->callTableAction(EditAction::class, $role, data: [
            'name' => $name = fake()->words(asText: true),
        ])
        ->assertHasNoTableActionErrors();

    expect($role->refresh())
        ->name->toBe($name);
});

it('can delete roles', function () {
    $permission = Permission::factory()
        ->has(Role::factory()->count(1))
        ->create();

    $role = $permission->roles->first();

    livewire(Resources\PermissionResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $permission,
    ])
    ->callTableAction(DeleteAction::class, $role);

    assertModelMissing($role);
});

it('can bulk delete roles', function () {
    // Arrange
    $permission = Permission::factory()
        ->has(Role::factory()->count(5))
        ->create();

    $roles = $permission->roles;

    // Act
    livewire(Resources\PermissionResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $permission,
    ])
        ->callTableBulkAction(DeleteBulkAction::class, $roles);

    // Assert
    foreach ($roles as $role) {
        assertModelMissing($role);
    }
});

it('can bulk detach roles', function () {
    // Arrange
    $permission = Permission::factory()
        ->has(Role::factory()->count(3))
        ->create();

    $roles = $permission->roles;

    // Act
    livewire(Resources\PermissionResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $permission,
    ])
        ->callTableBulkAction(DetachBulkAction::class, $roles)
        ->assertHasNoTableActionErrors();

    // Assert
    expect($permission->refresh()->roles->count())
        ->toBe(0);

});
