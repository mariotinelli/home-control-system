<?php

namespace Tests\Feature\Livewire\MarketStock\Withdrawals;

use App\Http\Livewire\MarketStock;
use App\Models\{MarketStockWithdrawal, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserGoldPermissions());

    $this->marketStock = \App\Models\MarketStock::factory()->create([
        'quantity' => 100,
    ]);

    $this->marketStockWithdraw = MarketStockWithdrawal::factory()->create([
        'market_stock_id' => $this->marketStock->id,
        'quantity'        => 50,
    ]);

    $this->marketStock->decrement('quantity', 50);

    actingAs($this->user);

});

it('should be able to delete a entry of the market stock', function () {

    expect($this->marketStock->quantity)
        ->toBe(100 - 50); // Actual Market Stock Quantity - Actual Withdrawal Quantity

    // Arrange
    $lw = livewire(MarketStock\Withdrawals\Destroy::class, [
        'marketStockWithdraw' => $this->marketStockWithdraw,
        'marketStock'         => $this->marketStock,
    ])
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('market-stock::withdraw::deleted');

    assertDatabaseMissing('market_stock_withdrawals', [
        'id' => $this->marketStockWithdraw->id,
    ]);

    assertDatabaseHas('market_stocks', [
        'id'       => $this->marketStock->id,
        'quantity' => 100,
    ]);

});
