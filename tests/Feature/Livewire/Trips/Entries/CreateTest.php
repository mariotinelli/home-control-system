<?php

namespace Tests\Feature\Livewire\Trips\Entries;

use App\Http\Livewire\Trips;
use App\Models\{Trip, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->trip = Trip::factory()->create([
        'city_id' => 2,
    ]);

    actingAs($this->user);

});

it('should be able to create a new trip entry', function () {

    livewire(Trips\Entries\Create::class, ['trip' => $this->trip])
        ->set('tripEntry.amount', 100)
        ->set('tripEntry.description', 'This is a new entry')
        ->set('tripEntry.date', now()->format('Y-m-d'))
        ->call('save');

    assertDatabaseHas('trip_entries', [
        'trip_id'     => $this->trip->id,
        'amount'      => 100,
        'description' => 'This is a new entry',
    ]);

});

test('amount is required', function () {

    livewire(Trips\Entries\Create::class, ['trip' => $this->trip])
        ->set('tripEntry.amount', null)
        ->call('save')
        ->assertHasErrors(['tripEntry.amount' => 'required']);

});

test('amount must be numeric', function () {

    livewire(Trips\Entries\Create::class, ['trip' => $this->trip])
        ->set('tripEntry.amount', 'abc')
        ->call('save')
        ->assertHasErrors(['tripEntry.amount' => 'numeric']);

});

test('amount must be greater than 0', function () {

    livewire(Trips\Entries\Create::class, ['trip' => $this->trip])
        ->set('tripEntry.amount', 0)
        ->call('save')
        ->assertHasErrors(['tripEntry.amount' => 'min']);

});

test('description is required', function () {

    livewire(Trips\Entries\Create::class, ['trip' => $this->trip])
        ->set('tripEntry.description', null)
        ->call('save')
        ->assertHasErrors(['tripEntry.description' => 'required']);

});

test('description must be a string', function () {

    livewire(Trips\Entries\Create::class, ['trip' => $this->trip])
        ->set('tripEntry.description', 123)
        ->call('save')
        ->assertHasErrors(['tripEntry.description' => 'string']);

});

test('description must be less than 255 characters', function () {

    livewire(Trips\Entries\Create::class, ['trip' => $this->trip])
        ->set('tripEntry.description', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['tripEntry.description' => 'max']);

});

test('date is required', function () {

    livewire(Trips\Entries\Create::class, ['trip' => $this->trip])
        ->set('tripEntry.date', null)
        ->call('save')
        ->assertHasErrors(['tripEntry.date' => 'required']);

});

test('date must be a date', function () {

    livewire(Trips\Entries\Create::class, ['trip' => $this->trip])
        ->set('tripEntry.date', 'abc')
        ->call('save')
        ->assertHasErrors(['tripEntry.date' => 'date']);

});
