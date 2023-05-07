<?php

namespace Tests\Feature\Livewire\Investments\Withdrawals;

use App\Http\Livewire\Investments;
use App\Models\{Investment, InvestmentWithdraw, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserSilverPermissions());

    $this->investment = Investment::factory()->create();

    $this->investmentWithdraw = InvestmentWithdraw::factory()->create([
        'investment_id' => $this->investment->id,
    ]);

    actingAs($this->user);

});

it('should be to update an investment withdraw', function () {

    assertDatabaseCount('investment_withdrawals', 1);

    // Act
    $lw = livewire(Investments\Withdrawals\Update::class, [
        'investment'         => $this->investment,
        'investmentWithdraw' => $this->investmentWithdraw,
    ])
        ->set('investmentWithdraw.amount', 200)
        ->set('investmentWithdraw.date', '2021-01-10')
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('investment::withdraw::updated');

    assertDatabaseHas('investment_withdrawals', [
        'investment_id' => $this->investment->id,
        'amount'        => 200,
        'date'          => '2021-01-10',
    ]);

});

test('amount is required', function () {

    // Act
    $lw = livewire(Investments\Withdrawals\Update::class, [
        'investment'         => $this->investment,
        'investmentWithdraw' => $this->investmentWithdraw,
    ])
        ->set('investmentWithdraw.amount', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentWithdraw.amount' => 'required']);

});

test('amount is numeric', function () {

    // Act
    $lw = livewire(Investments\Withdrawals\Update::class, [
        'investment'         => $this->investment,
        'investmentWithdraw' => $this->investmentWithdraw,
    ])
        ->set('investmentWithdraw.amount', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentWithdraw.amount' => 'numeric']);

});

test('amount is min 1', function () {

    // Act
    $lw = livewire(Investments\Withdrawals\Update::class, [
        'investment'         => $this->investment,
        'investmentWithdraw' => $this->investmentWithdraw,
    ])
        ->set('investmentWithdraw.amount', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentWithdraw.amount' => 'min']);

});

test('amount is max 1000', function () {

    // Act
    $lw = livewire(Investments\Withdrawals\Update::class, [
        'investment'         => $this->investment,
        'investmentWithdraw' => $this->investmentWithdraw,
    ])
        ->set('investmentWithdraw.amount', 1001)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentWithdraw.amount' => 'max']);

});

test('date is required', function () {

    // Act
    $lw = livewire(Investments\Withdrawals\Update::class, [
        'investment'         => $this->investment,
        'investmentWithdraw' => $this->investmentWithdraw,
    ])
        ->set('investmentWithdraw.date', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentWithdraw.date' => 'required']);

});

test('date is date', function () {

    // Act
    $lw = livewire(Investments\Withdrawals\Update::class, [
        'investment'         => $this->investment,
        'investmentWithdraw' => $this->investmentWithdraw,
    ])
        ->set('investmentWithdraw.date', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentWithdraw.date' => 'date']);

});
