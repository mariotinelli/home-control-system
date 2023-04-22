<?php

namespace Tests\Feature\Livewire\Investments\Withdrawals;

use App\Http\Livewire\Investments;
use App\Models\Investment;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->investment = Investment::factory()->create();

    actingAs($this->user);

});

it('should be create an investment withdraw', function () {

    assertDatabaseCount('investment_withdrawals', 0);

    // Act
    $lw = livewire(Investments\Withdrawals\Create::class, ['investment' => $this->investment])
        ->set('investmentWithdraw.amount', 100)
        ->set('investmentWithdraw.date', '2021-01-01')
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('investment::withdraw::created');

    assertDatabaseHas('investment_withdrawals', [
        'investment_id' => $this->investment->id,
        'amount' => 100,
        'date' => '2021-01-01',
    ]);

});

test('amount is required', function () {

    // Act
    $lw = livewire(Investments\Withdrawals\Create::class, ['investment' => $this->investment])
        ->set('investmentWithdraw.amount', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentWithdraw.amount' => 'required']);

});

test('amount is numeric', function () {

    // Act
    $lw = livewire(Investments\Withdrawals\Create::class, ['investment' => $this->investment])
        ->set('investmentWithdraw.amount', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentWithdraw.amount' => 'numeric']);

});

test('amount is min 1', function () {

    // Act
    $lw = livewire(Investments\Withdrawals\Create::class, ['investment' => $this->investment])
        ->set('investmentWithdraw.amount', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentWithdraw.amount' => 'min']);

});

test('amount is max 1000', function () {

    // Act
    $lw = livewire(Investments\Withdrawals\Create::class, ['investment' => $this->investment])
        ->set('investmentWithdraw.amount', 1001)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentWithdraw.amount' => 'max']);

});

test('date is required', function () {

    // Act
    $lw = livewire(Investments\Withdrawals\Create::class, ['investment' => $this->investment])
        ->set('investmentWithdraw.date', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentWithdraw.date' => 'required']);

});

test('date is date', function () {

    // Act
    $lw = livewire(Investments\Withdrawals\Create::class, ['investment' => $this->investment])
        ->set('investmentWithdraw.date', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentWithdraw.date' => 'date']);

});
