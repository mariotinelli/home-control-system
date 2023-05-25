<?php

use App\Filament;
use App\Models\{Role, User};
use App\Notifications\NewUserNotification;

use function Pest\Laravel\{actingAs, assertDatabaseHas, get};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->admin = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->admin->givePermissionTo(getManagerPermissions());

    actingAs($this->admin);

});

it('user with access_admin permission can access the page', function () {
    get(Filament\Resources\UserResource::getUrl('create'))->assertSuccessful();
});

it('user without access_admin permission can access the page', function () {
    $this->nonAdmin = User::factory()->create();

    actingAs($this->nonAdmin);

    get(Filament\Resources\UserResource::getUrl('create'))->assertForbidden();
});

it('can create a new user', function () {
    // Arrange
    $newData = User::factory()->make();

    $roles = Role::all()->random(2)->pluck('id')->toArray();

    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name'     => $newData->name,
            'email'    => $newData->email,
            'password' => '12345678',
            'roles'    => $roles,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    // Assert
    $databaseUser = User::where('email', $newData->email)->first();

    assertDatabaseHas(User::class, [
        'name'     => $newData->name,
        'email'    => $newData->email,
        'password' => Hash::check($databaseUser->password, '12345678'),
    ]);

    foreach ($roles as $role) {
        assertDatabaseHas('model_has_roles', [
            'role_id'    => $role,
            'model_type' => User::class,
            'model_id'   => User::where('email', $databaseUser->email)->first()->id,
        ]);

        foreach (Role::find($role)->permissions as $permission) {
            expect($databaseUser->hasPermissionTo($permission->name))
                ->toBeTrue();
        }
    }

});

it('can send email a new user', function () {
    // Arrange
    Notification::fake();

    $newData = User::factory()->make();

    $roles = Role::all()->random(2)->pluck('id')->toArray();

    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name'     => $newData->name,
            'email'    => $newData->email,
            'password' => '12345678',
            'roles'    => $roles,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    // Assert
    $databaseUser = User::where('email', $newData->email)->first();

    Notification::assertSentTo(
        [$databaseUser],
        NewUserNotification::class
    );

});

test('name input is required', function () {
    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required'])
        ->assertSeeHtml(__('validation.required', ['attribute' => 'nome']));
});

test('name input should be a string', function () {
    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => 123,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'string'])
        ->assertSeeHtml(__('validation.string', ['attribute' => 'nome']));
});

test('name input should be a maximum of 255 characters', function () {
    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'name' => str_repeat('a', 256),
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'max'])
        ->assertSeeHtml(__('validation.max.string', ['attribute' => 'nome', 'max' => 255]));
});

test('email input is required', function () {
    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'email' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'required'])
        ->assertSeeHtml(__('validation.required', ['attribute' => 'e-mail']));
});

test('email input should be a string', function () {
    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'email' => 123,
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'string'])
        ->assertSeeHtml(__('validation.string', ['attribute' => 'e-mail']));
});

test('email input should be a maximum of 255 characters', function () {
    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'email' => str_repeat('a', 256) . '@gmail.com',
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'max'])
        ->assertSeeHtml(__('validation.max.string', ['attribute' => 'e-mail', 'max' => 255]));
});

test('email input should be a valid email', function () {
    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'email' => 'invalid-email',
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'email'])
        ->assertSeeHtml(__('validation.email', ['attribute' => 'e-mail']));
});

test('email input should be unique', function () {
    // Arrange
    $user = User::factory()->create();

    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'email' => $user->email,
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'unique'])
        ->assertSeeHtml(__('validation.unique', ['attribute' => 'e-mail']));
});

test('password input is required', function () {
    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'password' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['password' => 'required'])
        ->assertSeeHtml(__('validation.required', ['attribute' => 'senha']));
});

test('password input should be a string', function () {
    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'password' => 123,
        ])
        ->call('create')
        ->assertHasFormErrors(['password' => 'string'])
        ->assertSeeHtml(__('validation.string', ['attribute' => 'senha']));
});

test('password input should be a minimum of 8 characters', function () {
    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'password' => str_repeat('a', 7),
        ])
        ->call('create')
        ->assertHasFormErrors(['password' => 'min'])
        ->assertSeeHtml(__('validation.min.string', ['attribute' => 'senha', 'min' => 8]));
});

test('password input should be a maximum of 12 characters', function () {
    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->fillForm([
            'password' => str_repeat('a', 13),
        ])
        ->call('create')
        ->assertHasFormErrors(['password' => 'max'])
        ->assertSeeHtml(__('validation.max.string', ['attribute' => 'senha', 'max' => 12]));
});

test('roles input is required', function () {
    // Act
    livewire(Filament\Resources\UserResource\Pages\CreateUser::class)
        ->call('create')
        ->assertHasFormErrors(['roles' => 'required'])
        ->assertSeeHtml(__('validation.required', ['attribute' => 'funções']));
});
