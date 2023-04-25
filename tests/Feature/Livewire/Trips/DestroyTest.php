<?php

namespace Tests\Feature\Livewire\Trips;

use App\Http\Livewire\Trips;
use App\Models\{Trip, User};

use function Pest\Laravel\{actingAs, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->trip = Trip::factory()->create([
        'city_id' => 2,
    ]);

    actingAs($this->user);

});

it('should be able to delete a trip', function () {

    // Act
    $lw = livewire(Trips\Destroy::class, ['trip' => $this->trip])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('trip::deleted');

    assertDatabaseMissing('trips', [
        'id' => $this->trip->id,
    ]);

});

todo('should be not able to delete a trip if that have entries');
