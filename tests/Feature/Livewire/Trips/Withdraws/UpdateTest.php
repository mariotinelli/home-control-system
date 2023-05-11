<?php

namespace Tests\Feature\Livewire\Trips\Entries;

use App\Http\Livewire\Trips;
use App\Models\{Trip, TripWithdraw, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserSilverPermissions());

    $this->trip = Trip::factory()->create([
        'city_id' => 2,
    ]);

    $this->tripWithdraw = TripWithdraw::factory()->create([
        'trip_id' => $this->trip->id,
    ]);

    actingAs($this->user);

});

it('should be able to create a new trip withdraw', function () {
    // Act
    $lw = livewire(Trips\Withdraws\Update::class, ['tripWithdraw' => $this->tripWithdraw])
        ->set('tripWithdraw.amount', 200)
        ->set('tripWithdraw.description', 'This is a updated withdraw')
        ->set('tripWithdraw.date', now()->format('Y-m-d'))
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('trip::withdraw::updated');

    assertDatabaseHas('trip_withdraws', [
        'trip_id'     => $this->trip->id,
        'amount'      => 200,
        'description' => 'This is a updated withdraw',
        'date'        => now()->format('Y-m-d'),
    ]);

});

test('amount is required', function () {

    livewire(Trips\Withdraws\Update::class, ['tripWithdraw' => $this->tripWithdraw])
        ->set('tripWithdraw.amount', null)
        ->call('save')
        ->assertHasErrors(['tripWithdraw.amount' => 'required']);

});

test('amount must be numeric', function () {

    livewire(Trips\Withdraws\Update::class, ['tripWithdraw' => $this->tripWithdraw])
        ->set('tripWithdraw.amount', 'abc')
        ->call('save')
        ->assertHasErrors(['tripWithdraw.amount' => 'numeric']);

});

test('amount must be greater than 0', function () {

    livewire(Trips\Withdraws\Update::class, ['tripWithdraw' => $this->tripWithdraw])
        ->set('tripWithdraw.amount', 0)
        ->call('save')
        ->assertHasErrors(['tripWithdraw.amount' => 'min']);

});

test('description is required', function () {

    livewire(Trips\Withdraws\Update::class, ['tripWithdraw' => $this->tripWithdraw])
        ->set('tripWithdraw.description', null)
        ->call('save')
        ->assertHasErrors(['tripWithdraw.description' => 'required']);

});

test('description must be a string', function () {

    livewire(Trips\Withdraws\Update::class, ['tripWithdraw' => $this->tripWithdraw])
        ->set('tripWithdraw.description', 123)
        ->call('save')
        ->assertHasErrors(['tripWithdraw.description' => 'string']);

});

test('description must be less than 255 characters', function () {

    livewire(Trips\Withdraws\Update::class, ['tripWithdraw' => $this->tripWithdraw])
        ->set('tripWithdraw.description', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['tripWithdraw.description' => 'max']);

});

test('date is required', function () {

    livewire(Trips\Withdraws\Update::class, ['tripWithdraw' => $this->tripWithdraw])
        ->set('tripWithdraw.date', null)
        ->call('save')
        ->assertHasErrors(['tripWithdraw.date' => 'required']);

});

test('date must be a date', function () {

    livewire(Trips\Withdraws\Update::class, ['tripWithdraw' => $this->tripWithdraw])
        ->set('tripWithdraw.date', 'abc')
        ->call('save')
        ->assertHasErrors(['tripWithdraw.date' => 'date']);

});
