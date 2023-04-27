<?php

namespace Tests\Feature\Livewire\Trips\Entries;

use App\Http\Livewire\Trips;
use App\Models\{Trip, TripEntry, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->trip = Trip::factory()->create([
        'city_id' => 2,
    ]);

    $this->tripEntry = TripEntry::factory()->create([
        'trip_id' => $this->trip->id,
    ]);

    actingAs($this->user);

});

it('should be able to delete a trip entry', function () {

    assertDatabaseHas('trip_entries', [
        'id' => $this->tripEntry->id,
    ]);

    assertDatabaseCount('trip_entries', 1);

    $lw = livewire(Trips\Entries\Destroy::class, [
        'tripEntry' => $this->tripEntry,
    ])
        ->call('save');

    $lw->assertHasNoErrors()
        ->assertEmitted('trip::entry::deleted');

    assertDatabaseMissing('trip_entries', [
        'id' => $this->tripEntry->id,
    ]);

    assertDatabaseCount('trip_entries', 0);

});
