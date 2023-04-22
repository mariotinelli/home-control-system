<?php

namespace Tests\Feature\Livewire\Goals\Withdrawals;

use App\Http\Livewire\Goals;
use App\Models\Goal;
use App\Models\GoalWithdraw;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->goal = Goal::factory()->create();

    $this->goalWithdraw = GoalWithdraw::factory()->create([
        'goal_id' => $this->goal->id,
        'amount' => 100,
    ]);

    actingAs($this->user);

});

it('should be to delete an goal withdraw', function () {

    assertDatabaseCount('goal_withdraws', 1);

    // Act
    $lw = livewire(Goals\Withdrawals\Destroy::class, [
        'goal' => $this->goal,
        'goalWithdraw' => $this->goalWithdraw
    ])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('goal::withdraw::deleted');

    assertDatabaseMissing('goal_withdraws', [
        'id' => $this->goalWithdraw->id,
    ]);

});
