<?php

namespace Tests\Feature\Livewire\Goals\Entries;

use App\Http\Livewire\Goals;
use App\Models\Goal;
use App\Models\GoalEntry;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->goal = Goal::factory()->create();

    $this->goalEntry = GoalEntry::factory()->create([
        'goal_id' => $this->goal->id,
        'amount' => 100,
    ]);

    actingAs($this->user);

});

it('should be to delete an goal entry', function () {

    assertDatabaseCount('goal_entries', 1);

    // Act
    $lw = livewire(Goals\Entries\Destroy::class, [
        'goal' => $this->goal,
        'goalEntry' => $this->goalEntry
    ])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('goal::entry::deleted');

    assertDatabaseMissing('goal_entries', [
        'id' => $this->goalEntry->id,
    ]);

});
