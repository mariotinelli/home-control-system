<?php

namespace Tests\Feature\Livewire\Goals\Withdrawals;

use App\Http\Livewire\Goals;
use App\Models\{Goal, GoalWithdraw, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserPermissions());

    $this->goal = Goal::factory()->create();

    $this->goalWithdraw = GoalWithdraw::factory()->create([
        'goal_id' => $this->goal->id,
        'amount'  => 100,
    ]);

    actingAs($this->user);

});

it('should be to delete an goal withdraw', function () {

    assertDatabaseCount('goal_withdraws', 1);

    // Act
    $lw = livewire(Goals\Withdrawals\Destroy::class, [
        'goal'         => $this->goal,
        'goalWithdraw' => $this->goalWithdraw,
    ])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('goal::withdraw::deleted');

    assertDatabaseMissing('goal_withdraws', [
        'id' => $this->goalWithdraw->id,
    ]);

});
