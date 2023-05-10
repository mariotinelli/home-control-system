<?php

namespace Tests\Feature\Livewire\Trips\Withdraws;

use App\Http\Livewire\Trips;
use App\Models\{Trip, TripWithdraw, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserSilverPermissions());

    $this->trip = Trip::factory()->create([
        'city_id' => 2,
    ]);

    $this->tripWithdraw = TripWithdraw::factory()->create([
        'trip_id' => $this->trip->id,
    ]);

    actingAs($this->user);

});

it('should be able to delete a trip withdraw', function () {

    assertDatabaseHas('trip_withdraws', [
        'id' => $this->tripWithdraw->id,
    ]);

    assertDatabaseCount('trip_withdraws', 1);

    $lw = livewire(Trips\Withdraws\Destroy::class, [
        'tripWithdraw' => $this->tripWithdraw,
    ])
        ->call('save');

    $lw->assertHasNoErrors()
        ->assertEmitted('trip::withdraw::deleted');

    assertDatabaseMissing('trip_withdraws', [
        'id' => $this->tripWithdraw->id,
    ]);

    assertDatabaseCount('trip_withdraws', 0);

});
