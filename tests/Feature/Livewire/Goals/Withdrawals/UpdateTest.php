<?php

namespace Tests\Feature\Livewire\Goals\Withdrawals;

use App\Http\Livewire\Goals;
use App\Models\Goal;
use App\Models\GoalWithdraw;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->goal = Goal::factory()->create();

    $this->goalWithdraw = GoalWithdraw::factory()->create([
        'goal_id' => $this->goal->id,
    ]);

    actingAs($this->user);

});

it('should be to update an goal withdraw', function () {

    assertDatabaseCount('goal_withdraws', 1);

    // Act
    $lw = livewire(Goals\Withdrawals\Update::class, [
        'goal' => $this->goal,
        'goalWithdraw' => $this->goalWithdraw
    ])
        ->set('goalWithdraw.amount', 200)
        ->set('goalWithdraw.date', '2021-01-10')
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('goal::withdraw::updated');

    assertDatabaseHas('goal_withdraws', [
        'id' => $this->goalWithdraw->id,
        'goal_id' => $this->goal->id,
        'amount' => 200,
        'date' => '2021-01-10',
    ]);

});

test('amount is required', function () {

    // Act
    $lw = livewire(Goals\Withdrawals\Update::class, [
        'goal' => $this->goal,
        'goalWithdraw' => $this->goalWithdraw
    ])
        ->set('goalWithdraw.amount', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalWithdraw.amount' => 'required']);

});

test('amount is numeric', function () {

    // Act
    $lw = livewire(Goals\Withdrawals\Update::class, [
        'goal' => $this->goal,
        'goalWithdraw' => $this->goalWithdraw
    ])
        ->set('goalWithdraw.amount', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalWithdraw.amount' => 'numeric']);

});

test('amount is min 1', function () {

    // Act
    $lw = livewire(Goals\Withdrawals\Update::class, [
        'goal' => $this->goal,
        'goalWithdraw' => $this->goalWithdraw
    ])
        ->set('goalWithdraw.amount', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalWithdraw.amount' => 'min']);

});

test('amount is max 1000', function () {

    // Act
    $lw = livewire(Goals\Withdrawals\Update::class, [
        'goal' => $this->goal,
        'goalWithdraw' => $this->goalWithdraw
    ])
        ->set('goalWithdraw.amount', 1001)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalWithdraw.amount' => 'max']);

});

test('date is required', function () {

    // Act
    $lw = livewire(Goals\Withdrawals\Update::class, [
        'goal' => $this->goal,
        'goalWithdraw' => $this->goalWithdraw
    ])
        ->set('goalWithdraw.date', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalWithdraw.date' => 'required']);

});

test('date is date', function () {

    // Act
    $lw = livewire(Goals\Withdrawals\Update::class, [
        'goal' => $this->goal,
        'goalWithdraw' => $this->goalWithdraw
    ])
        ->set('goalWithdraw.date', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalWithdraw.date' => 'date']);

});
