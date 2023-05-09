<?php

use App\Filament;
use App\Models\{Role, User};

use function Pest\Laravel\{actingAs, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->admin = User::factory()->create();

    $this->admin->givePermissionTo(getAdminPermissions());

    actingAs($this->admin);

});

it('user with access_admin permission can access the page', function () {

    get(Filament\Resources\RoleResource::getUrl('edit', [
        'record' => Role::factory()->create(),
    ]))->assertSuccessful();

});

it('user without access_admin permission can access the page', function () {

    // Arrange
    $nonAdmin = User::factory()->create();

    // Act
    actingAs($nonAdmin);

    get(Filament\Resources\RoleResource::getUrl('edit', [
        'record' => Role::factory()->create(),
    ]))->assertForbidden();

});

it('can retrieve data', function () {
    // Arrange
    $role = Role::factory()->create();

    livewire(Filament\Resources\RoleResource\Pages\EditRole::class, [
        'record' => $role->getRouteKey(),
    ])
        ->assertFormSet([
            'name' => $role->name,
        ]);
});

it('can update a role', function () {
    // Arrange
    $role = Role::factory()->create();

    $newData = Role::factory()->make();

    // Act
    livewire(Filament\Resources\RoleResource\Pages\EditRole::class, [
        'record' => $role->getRouteKey(),
    ])
        ->fillForm([
            'name' => $newData->name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($role->refresh())
        ->name->toBe($newData->name);

});

test('name input is required', function () {
    // Arrange
    $role = Role::factory()->create();

    // Act
    livewire(Filament\Resources\RoleResource\Pages\EditRole::class, [
        'record' => $role->getRouteKey(),
    ])
        ->fillForm([
            'name' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'required'])
        ->assertSeeHtml(__('validation.required', ['attribute' => 'nome']));
});

test('name input should be a string', function () {
    // Arrange
    $role = Role::factory()->create();

    // Act
    livewire(Filament\Resources\RoleResource\Pages\EditRole::class, [
        'record' => $role->getRouteKey(),
    ])
        ->fillForm([
            'name' => 123,
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'string'])
        ->assertSeeHtml(__('validation.string', ['attribute' => 'nome']));
});

test('name input should be a maximum of 255 characters', function () {
    // Arrange
    $role = Role::factory()->create();

    // Act
    livewire(Filament\Resources\RoleResource\Pages\EditRole::class, [
        'record' => $role->getRouteKey(),
    ])
        ->fillForm([
            'name' => str_repeat('a', 256),
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'max'])
        ->assertSeeHtml(__('validation.max.string', ['attribute' => 'nome', 'max' => 255]));
});

test('name input should be unique', function () {
    // Arrange
    $role = Role::factory()->create();

    $role2 = Role::factory()->create();

    // Act
    livewire(Filament\Resources\RoleResource\Pages\EditRole::class, [
        'record' => $role->getRouteKey(),
    ])
        ->fillForm([
            'name' => $role2->name,
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique'])
        ->assertSeeHtml(__('validation.unique', ['attribute' => 'nome']));
});

test('name input should be unique, except for itself', function () {
    // Arrange
    $role = Role::factory()->create();

    // Act
    livewire(Filament\Resources\RoleResource\Pages\EditRole::class, [
        'record' => $role->getRouteKey(),
    ])
        ->fillForm([
            'name' => $role->name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();
});
