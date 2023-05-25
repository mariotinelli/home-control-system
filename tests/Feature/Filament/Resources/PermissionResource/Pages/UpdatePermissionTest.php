<?php

use App\Filament;
use App\Models\{Permission, User};

use function Pest\Laravel\{actingAs, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->admin = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->admin->givePermissionTo(getManagerPermissions());

    actingAs($this->admin);

});

it('user with access admin permission can access the page', function () {

    get(Filament\Resources\PermissionResource::getUrl('edit', [
        'record' => Permission::factory()->create(),
    ]))->assertSuccessful();

});

it('user without access admin permission can access the page', function () {

    // Arrange
    $nonAdmin = User::factory()->create();

    // Act
    actingAs($nonAdmin);

    get(Filament\Resources\PermissionResource::getUrl('edit', [
        'record' => Permission::factory()->create(),
    ]))->assertForbidden();

});

it('can retrieve data', function () {
    // Arrange
    $permission = Permission::factory()->create();

    livewire(Filament\Resources\PermissionResource\Pages\EditPermission::class, [
        'record' => $permission->getRouteKey(),
    ])
        ->assertFormSet([
            'name' => $permission->name,
        ]);
});

it('can update a permission', function () {
    // Arrange
    $permission = Permission::factory()->create();

    $newData = Permission::factory()->make();

    // Act
    livewire(Filament\Resources\PermissionResource\Pages\EditPermission::class, [
        'record' => $permission->getRouteKey(),
    ])
        ->fillForm([
            'name' => $newData->name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($permission->refresh())
        ->name->toBe($newData->name);

});

test('name input is required', function () {
    // Arrange
    $permission = Permission::factory()->create();

    // Act
    livewire(Filament\Resources\PermissionResource\Pages\EditPermission::class, [
        'record' => $permission->getRouteKey(),
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
    $permission = Permission::factory()->create();

    // Act
    livewire(Filament\Resources\PermissionResource\Pages\EditPermission::class, [
        'record' => $permission->getRouteKey(),
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
    $permission = Permission::factory()->create();

    // Act
    livewire(Filament\Resources\PermissionResource\Pages\EditPermission::class, [
        'record' => $permission->getRouteKey(),
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
    $permission = Permission::factory()->create();

    $permission2 = Permission::factory()->create();

    // Act
    livewire(Filament\Resources\PermissionResource\Pages\EditPermission::class, [
        'record' => $permission->getRouteKey(),
    ])
        ->fillForm([
            'name' => $permission2->name,
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'unique'])
        ->assertSeeHtml(__('validation.unique', ['attribute' => 'nome']));
});

test('name input should be unique, except for itself', function () {
    // Arrange
    $permission = Permission::factory()->create();

    // Act
    livewire(Filament\Resources\PermissionResource\Pages\EditPermission::class, [
        'record' => $permission->getRouteKey(),
    ])
        ->fillForm([
            'name' => $permission->name,
        ])
        ->call('save')
        ->assertHasNoFormErrors();
});
