<?php

namespace Tests\Feature\Livewire\Investments\Withdrawals;

use App\Http\Livewire\Investments;
use App\Models\{Investment, InvestmentWithdraw, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserSilverPermissions());

    $this->investment = Investment::factory()->create();

    $this->investmentWithdraw = InvestmentWithdraw::factory()->create([
        'investment_id' => $this->investment->id,
        'amount'        => 100,
    ]);

    actingAs($this->user);

});

it('should be to delete an investment withdraw', function () {

    assertDatabaseCount('investment_withdrawals', 1);

    // Act
    $lw = livewire(Investments\Withdrawals\Destroy::class, [
        'investment'         => $this->investment,
        'investmentWithdraw' => $this->investmentWithdraw,
    ])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('investment::withdraw::deleted');

    assertDatabaseMissing('investment_withdrawals', [
        'id' => $this->investmentWithdraw->id,
    ]);

});
