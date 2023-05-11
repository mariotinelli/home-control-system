<?php

namespace Tests\Feature\Livewire\Trips;

use App\Http\Livewire\Trips;
use App\Models\{City, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserSilverPermissions());

    actingAs($this->user);

});

it('should be able to create a new trip', function () {

    // Act
    $lw = livewire(Trips\Create::class)
        ->set('trip.city_id', 2)
        ->set('trip.month', '12/2020')
        ->set('trip.description', 'Test Description')
        ->set('trip.total_value', 1000)
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('trip::created');

    assertDatabaseHas('trips', [
        'city_id'     => 2,
        'month'       => '12/2020',
        'description' => 'Test Description',
        'total_value' => 1000,
    ]);

});

test('city is required', function () {

    // Act
    $lw = livewire(Trips\Create::class)
        ->set('trip.city_id', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['trip.city_id' => 'required']);

});

test('city should be a integer', function () {

    // Act
    $lw = livewire(Trips\Create::class)
        ->set('trip.city_id', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['trip.city_id' => 'integer']);

});

test('city should be exists', function () {

    // Act
    $lw = livewire(Trips\Create::class)
        ->set('trip.city_id', City::count() + 1)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['trip.city_id' => 'exists']);

});

test('description is required', function () {

    // Act
    $lw = livewire(Trips\Create::class)
        ->set('trip.description', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['trip.description' => 'required']);

});

test('description should be a string', function () {

    // Act
    $lw = livewire(Trips\Create::class)
        ->set('trip.description', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['trip.description' => 'string']);

});

test('description should be max 255 characters', function () {

    // Act
    $lw = livewire(Trips\Create::class)
        ->set('trip.description', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['trip.description' => 'max']);

});

test('total value is required', function () {

    // Act
    $lw = livewire(Trips\Create::class)
        ->set('trip.total_value', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['trip.total_value' => 'required']);

});

test('total value should be a numeric', function () {

    // Act
    $lw = livewire(Trips\Create::class)
        ->set('trip.total_value', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['trip.total_value' => 'numeric']);

});

test('total value should be greater than 0', function () {

    // Act
    $lw = livewire(Trips\Create::class)
        ->set('trip.total_value', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['trip.total_value' => 'min']);

});

test('month is required', function () {

    // Act
    $lw = livewire(Trips\Create::class)
        ->set('trip.month', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['trip.month' => 'required']);

});

test('month should be a date in the format m/Y', function () {

    // Act
    $lw = livewire(Trips\Create::class)
        ->set('trip.month', '2021-01-01')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['trip.month' => 'date_format']);

});
