<?php

namespace Tests\Feature\Livewire\Goals\Withdrawals;

use App\Http\Livewire\Goals;
use App\Models\{Goal, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->goal = Goal::factory()->create();

    actingAs($this->user);

});

it('should be create an goal withdraw', function () {

    assertDatabaseCount('goal_withdraws', 0);

    // Act
    $lw = livewire(Goals\Withdrawals\Create::class, ['goal' => $this->goal])
        ->set('goalWithdraw.amount', 100)
        ->set('goalWithdraw.date', '2021-01-01')
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('goal::withdraw::created');

    assertDatabaseHas('goal_withdraws', [
        'goal_id' => $this->goal->id,
        'amount'  => 100,
        'date'    => '2021-01-01',
    ]);

});

test('amount is required', function () {

    // Act
    $lw = livewire(Goals\Withdrawals\Create::class, ['goal' => $this->goal])
        ->set('goalWithdraw.amount', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalWithdraw.amount' => 'required']);

});

test('amount is numeric', function () {

    // Act
    $lw = livewire(Goals\Withdrawals\Create::class, ['goal' => $this->goal])
        ->set('goalWithdraw.amount', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalWithdraw.amount' => 'numeric']);

});

test('amount is min 1', function () {

    // Act
    $lw = livewire(Goals\Withdrawals\Create::class, ['goal' => $this->goal])
        ->set('goalWithdraw.amount', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalWithdraw.amount' => 'min']);

});

test('amount is max 1000', function () {

    // Act
    $lw = livewire(Goals\Withdrawals\Create::class, ['goal' => $this->goal])
        ->set('goalWithdraw.amount', 1001)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalWithdraw.amount' => 'max']);

});

test('date is required', function () {

    // Act
    $lw = livewire(Goals\Withdrawals\Create::class, ['goal' => $this->goal])
        ->set('goalWithdraw.date', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalWithdraw.date' => 'required']);

});

test('date is date', function () {

    // Act
    $lw = livewire(Goals\Withdrawals\Create::class, ['goal' => $this->goal])
        ->set('goalWithdraw.date', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['goalWithdraw.date' => 'date']);

});
