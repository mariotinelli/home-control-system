<?php

namespace Tests\Feature\Livewire\Goals\Entries;

use App\Http\Livewire\Goals;
use App\Models\Goal;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->goal = Goal::factory()->create();

    actingAs($this->user);

});

it('should be create an goal entry', function () {

    assertDatabaseCount('goal_entries', 0);

    // Act
    $lw = livewire(Goals\Entries\Create::class, ['goal' => $this->goal])
        ->set('goalEntry.amount', 100)
        ->set('goalEntry.date', '2021-01-01')
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('goal::entry::created');

    assertDatabaseHas('goal_entries', [
        'goal_id' => $this->goal->id,
        'amount' => 100,
        'date' => '2021-01-01',
    ]);

});

test('amount is required', function () {

    // Act
    $lw = livewire(Goals\Entries\Create::class, ['goal' => $this->goal])
        ->set('goalEntry.amount', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalEntry.amount' => 'required']);

});

test('amount is numeric', function () {

    // Act
    $lw = livewire(Goals\Entries\Create::class, ['goal' => $this->goal])
        ->set('goalEntry.amount', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalEntry.amount' => 'numeric']);

});

test('amount is min 1', function () {

    // Act
    $lw = livewire(Goals\Entries\Create::class, ['goal' => $this->goal])
        ->set('goalEntry.amount', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalEntry.amount' => 'min']);

});

test('amount is max 1000', function () {

    // Act
    $lw = livewire(Goals\Entries\Create::class, ['goal' => $this->goal])
        ->set('goalEntry.amount', 1001)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalEntry.amount' => 'max']);

});

test('date is required', function () {

    // Act
    $lw = livewire(Goals\Entries\Create::class, ['goal' => $this->goal])
        ->set('goalEntry.date', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalEntry.date' => 'required']);

});

test('date is date', function () {

    // Act
    $lw = livewire(Goals\Entries\Create::class, ['goal' => $this->goal])
        ->set('goalEntry.date', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalEntry.date' => 'date']);

});
