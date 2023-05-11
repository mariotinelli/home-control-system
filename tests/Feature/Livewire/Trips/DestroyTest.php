<?php

namespace Tests\Feature\Livewire\Trips;

use App\Http\Livewire\Trips;
use App\Models\{Trip, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserSilverPermissions());

    $this->user->trips()->save(
        $this->trip = Trip::factory()->create([
            'city_id' => 2,
        ])
    );

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

it('should be not able to delete a trip if that have entries', function () {

    // Arrange
    $this->trip->entries()->create([
        'amount'      => 100,
        'description' => 'Teste',
        'date'        => now()->format('Y-m-d'),
    ]);

    // Act
    $lw = livewire(Trips\Destroy::class, ['trip' => $this->trip])
        ->call('save');

    // Assert
    $lw->assertHasErrors(['trip'])
        ->assertNotEmitted('trip::deleted');

    assertDatabaseHas('trips', [
        'id' => $this->trip->id,
    ]);

});

it('should be not able to delete a trip if that have withdraws', function () {

    // Arrange
    $this->trip->withdraws()->create([
        'amount'      => 100,
        'description' => 'Teste',
        'date'        => now()->format('Y-m-d'),
    ]);

    // Act
    $lw = livewire(Trips\Destroy::class, ['trip' => $this->trip])
        ->call('save');

    // Assert
    $lw->assertHasErrors(['trip'])
        ->assertNotEmitted('trip::deleted');

    assertDatabaseHas('trips', [
        'id' => $this->trip->id,
    ]);

});

todo('show dialog error if trip have entries');

todo('show dialog error if trip have withdraws');
