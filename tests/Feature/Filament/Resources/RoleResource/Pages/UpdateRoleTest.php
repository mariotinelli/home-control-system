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
    get(Filament\Resources\UserResource::getUrl('edit', [
        'record' => User::factory()->create(),
    ]))->assertSuccessful();
});

it('user without access_admin permission can access the page', function () {
    // Arrange
    $nonAdmin = User::factory()->create();

    // Act
    actingAs($nonAdmin);

    get(Filament\Resources\UserResource::getUrl('edit', [
        'record' => User::factory()->create(),
    ]))->assertForbidden();
});

it('can retrieve data', function () {
    // Arrange
    $user = User::factory()->create();

    $user->roles()->attach(Role::all()->random(2)->pluck('id')->toArray());

    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->assertFormSet([
            'name'  => $user->name,
            'email' => $user->email,
            'roles' => $user->roles()->pluck('id')->toArray(),
        ]);
});

it('can update a user', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    $user->roles()->attach(Role::all()->random(2)->pluck('id')->toArray());

    $newData = User::factory()->make();

    $newDataPassword = '123456789';

    $roles = Role::all()->random(2)->pluck('id')->toArray();

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'name'     => $newData->name,
            'email'    => $newData->email,
            'password' => $newDataPassword,
            'roles'    => $roles,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->refresh())
        ->name->toBe($newData->name)
        ->email->toBe($newData->email)
        ->and(Hash::check($newDataPassword, $user->refresh()->password))->toBeTrue()
        ->and($roles)->each(function ($role) use ($user) {
            expect($user->refresh()->roles()->pluck('id'))->toContain($role->value);
        });

});

test('name input is required', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
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
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
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
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'name' => str_repeat('a', 256),
        ])
        ->call('save')
        ->assertHasFormErrors(['name' => 'max'])
        ->assertSeeHtml(__('validation.max.string', ['attribute' => 'nome', 'max' => 255]));
});

test('email input is required', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'email' => null,
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'required'])
        ->assertSeeHtml(__('validation.required', ['attribute' => 'e-mail']));
});

test('email input should be a string', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'email' => 123,
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'string'])
        ->assertSeeHtml(__('validation.string', ['attribute' => 'e-mail']));
});

test('email input should be a maximum of 255 characters', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'email' => str_repeat('a', 256) . '@gmail.com',
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'max'])
        ->assertSeeHtml(__('validation.max.string', ['attribute' => 'e-mail', 'max' => 255]));
});

test('email input should be a valid email', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'email' => 'invalid-email',
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'email'])
        ->assertSeeHtml(__('validation.email', ['attribute' => 'e-mail']));
});

test('email input should be unique', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    $newData = User::factory()->create();

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'email' => $newData->email,
        ])
        ->call('save')
        ->assertHasFormErrors(['email' => 'unique'])
        ->assertSeeHtml(__('validation.unique', ['attribute' => 'e-mail']));
});

test('email input should be ignore unique for that user', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'email' => $user->email,
        ])
        ->call('save')
        ->assertHasNoFormErrors(['email' => 'unique']);
});

test('password input is nullable', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'password' => null,
        ])
        ->call('save')
        ->assertHasNoFormErrors(['password' => 'required']);
});

test('password input should be a string', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'password' => 123,
        ])
        ->call('save')
        ->assertHasFormErrors(['password' => 'string'])
        ->assertSeeHtml(__('validation.string', ['attribute' => 'senha']));
});

test('password input should be a minimum of 8 characters', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'password' => str_repeat('a', 7),
        ])
        ->call('save')
        ->assertHasFormErrors(['password' => 'min'])
        ->assertSeeHtml(__('validation.min.string', ['attribute' => 'senha', 'min' => 8]));
});

test('password input should be a maximum of 12 characters', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->fillForm([
            'password' => str_repeat('a', 13),
        ])
        ->call('save')
        ->assertHasFormErrors(['password' => 'max'])
        ->assertSeeHtml(__('validation.max.string', ['attribute' => 'senha', 'max' => 12]));
});

test('roles input is required', function () {
    // Arrange
    $user = User::factory()->create([
        'password' => '12345678',
    ]);

    // Act
    livewire(Filament\Resources\UserResource\Pages\EditUser::class, [
        'record' => $user->getRouteKey(),
    ])
        ->call('save')
        ->assertHasFormErrors(['roles' => 'required'])
        ->assertSeeHtml(__('validation.required', ['attribute' => 'funções']));
});
