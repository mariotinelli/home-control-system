<?php

namespace Tests\Feature\Livewire\Goals\Entries;

use App\Http\Livewire\Goals;
use App\Models\{Goal, GoalEntry, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseMissing};
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

it('should be to delete an goal entry', function () {

    assertDatabaseCount('goal_entries', 1);

    // Act
    $lw = livewire(Goals\Entries\Destroy::class, [
        'goal'      => $this->goal,
        'goalEntry' => $this->goalEntry,
    ])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('goal::entry::deleted');

    assertDatabaseMissing('goal_entries', [
        'id' => $this->goalEntry->id,
    ]);

});
