<?php

namespace Tests\Feature\Livewire\Goals\Entries;

use App\Http\Livewire\Goals;
use App\Models\{Goal, GoalEntry, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserPermissions());

    $this->goal = Goal::factory()->create();

    $this->goalEntry = GoalEntry::factory()->create([
        'goal_id' => $this->goal->id,
        'amount'  => 100,
    ]);

    actingAs($this->user);

});

it('should be to update an goal entry', function () {

    assertDatabaseCount('goal_entries', 1);

    // Act
    $lw = livewire(Goals\Entries\Update::class, [
        'goal'      => $this->goal,
        'goalEntry' => $this->goalEntry,
    ])
        ->set('goalEntry.amount', 200)
        ->set('goalEntry.date', '2021-01-10')
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('goal::entry::updated');

    assertDatabaseHas('goal_entries', [
        'goal_id' => $this->goal->id,
        'amount'  => 200,
        'date'    => '2021-01-10',
    ]);

});

test('amount is required', function () {

    // Act
    $lw = livewire(Goals\Entries\Update::class, [
        'goal'      => $this->goal,
        'goalEntry' => $this->goalEntry,
    ])
        ->set('goalEntry.amount', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalEntry.amount' => 'required']);

});

test('amount is numeric', function () {

    // Act
    $lw = livewire(Goals\Entries\Update::class, [
        'goal'      => $this->goal,
        'goalEntry' => $this->goalEntry,
    ])
        ->set('goalEntry.amount', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalEntry.amount' => 'numeric']);

});

test('amount is min 1', function () {

    // Act
    $lw = livewire(Goals\Entries\Update::class, [
        'goal'      => $this->goal,
        'goalEntry' => $this->goalEntry,
    ])
        ->set('goalEntry.amount', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalEntry.amount' => 'min']);

});

test('amount is max 1000', function () {

    // Act
    $lw = livewire(Goals\Entries\Update::class, [
        'goal'      => $this->goal,
        'goalEntry' => $this->goalEntry,
    ])
        ->set('goalEntry.amount', 1001)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalEntry.amount' => 'max']);

});

test('date is required', function () {

    // Act
    $lw = livewire(Goals\Entries\Update::class, [
        'goal'      => $this->goal,
        'goalEntry' => $this->goalEntry,
    ])
        ->set('goalEntry.date', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalEntry.date' => 'required']);

});

test('date is date', function () {

    // Act
    $lw = livewire(Goals\Entries\Update::class, [
        'goal'      => $this->goal,
        'goalEntry' => $this->goalEntry,
    ])
        ->set('goalEntry.date', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalEntry.date' => 'date']);

});
