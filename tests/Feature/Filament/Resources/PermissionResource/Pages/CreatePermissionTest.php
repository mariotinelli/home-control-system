<?php

use App\Filament;
use App\Models\{Permission, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->admin = User::factory()->create();

    $this->admin->givePermissionTo(getAdminPermissions());

    actingAs($this->admin);

});

it('user with access admin permission can access the page', function () {

    get(Filament\Resources\PermissionResource::getUrl('create'))
        ->assertSuccessful();

});

it('user without access admin permission can access the page', function () {
    // Arrange
    $this->nonAdmin = User::factory()->create();

    // Act
    actingAs($this->nonAdmin);

    get(Filament\Resources\PermissionResource::getUrl('create'))
        ->assertForbidden();

});

it('can create a new permission', function () {
    // Arrange
    $newData = Permission::factory()->make();

    // Act
    livewire(Filament\Resources\PermissionResource\Pages\CreatePermission::class)
        ->fillForm([
            'name' => $newData->name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    // Assert
    assertDatabaseHas(Permission::class, [
        'name' => $newData->name,
    ]);

});

test('name input is required', function () {
    // Act
    livewire(Filament\Resources\PermissionResource\Pages\CreatePermission::class)
        ->fillForm([
            'name' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required'])
        ->assertSeeHtml(__('validation.required', ['attribute' => 'nome']));
});

test('name input should be a string', function () {
    // Act
    livewire(Filament\Resources\PermissionResource\Pages\CreatePermission::class)
        ->fillForm([
            'name' => 123,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'string'])
        ->assertSeeHtml(__('validation.string', ['attribute' => 'nome']));
});

test('name input should be a maximum of 255 characters', function () {
    // Act
    livewire(Filament\Resources\PermissionResource\Pages\CreatePermission::class)
        ->fillForm([
            'name' => str_repeat('a', 256),
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'max'])
        ->assertSeeHtml(__('validation.max.string', ['attribute' => 'nome', 'max' => 255]));
});

test('name input should be unique', function () {
    // Arrange
    $role = Permission::factory()->create();

    // Act
    livewire(Filament\Resources\PermissionResource\Pages\CreatePermission::class)
        ->fillForm([
            'name' => $role->name,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique'])
        ->assertSeeHtml(__('validation.unique', ['attribute' => 'nome']));
});
