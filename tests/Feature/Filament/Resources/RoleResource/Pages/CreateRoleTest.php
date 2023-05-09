<?php

use App\Filament;
use App\Models\{Role, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->admin = User::factory()->create();

    $this->admin->givePermissionTo(getAdminPermissions());

    actingAs($this->admin);

});

it('user with access_admin permission can access the page', function () {

    get(Filament\Resources\RoleResource::getUrl('create'))
        ->assertSuccessful();

});

it('user without access_admin permission can access the page', function () {
    // Arrange
    $this->nonAdmin = User::factory()->create();

    // Act
    actingAs($this->nonAdmin);

    get(Filament\Resources\RoleResource::getUrl('create'))
        ->assertForbidden();

});

it('can create a new role', function () {
    // Arrange
    $newData = Role::factory()->make();

    // Act
    livewire(Filament\Resources\RoleResource\Pages\CreateRole::class)
        ->fillForm([
            'name' => $newData->name,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    // Assert
    assertDatabaseHas(Role::class, [
        'name' => $newData->name,
    ]);

});

test('name input is required', function () {
    // Act
    livewire(Filament\Resources\RoleResource\Pages\CreateRole::class)
        ->fillForm([
            'name' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required'])
        ->assertSeeHtml(__('validation.required', ['attribute' => 'nome']));
});

test('name input should be a string', function () {
    // Act
    livewire(Filament\Resources\RoleResource\Pages\CreateRole::class)
        ->fillForm([
            'name' => 123,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'string'])
        ->assertSeeHtml(__('validation.string', ['attribute' => 'nome']));
});

test('name input should be a maximum of 255 characters', function () {
    // Act
    livewire(Filament\Resources\RoleResource\Pages\CreateRole::class)
        ->fillForm([
            'name' => str_repeat('a', 256),
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'max'])
        ->assertSeeHtml(__('validation.max.string', ['attribute' => 'nome', 'max' => 255]));
});
