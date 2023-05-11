<?php

namespace Tests\Feature\Livewire\MarketStock\Entries;

use App\Http\Livewire\MarketStock;
use App\Models\{Market, User};

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

    $this->market = Market::factory()->create();

    actingAs($this->user);

});

it('should be create a new withdraw of the market stock', function () {

    expect($this->marketStock->quantity)
        ->toBe(100);

    // Act
    $lw = livewire(MarketStock\Withdrawals\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockWithdraw.market_stock_id', $this->marketStock->id)
        ->set('marketStockWithdraw.market_id', $this->market->id)
        ->set('marketStockWithdraw.price', 10)
        ->set('marketStockWithdraw.quantity', 30)
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('market-stock::withdrawal::created');

    assertDatabaseHas('market_stock_withdrawals', [
        'market_stock_id' => $this->marketStock->id,
        'market_id'       => $this->market->id,
        'price'           => 10,
        'quantity'        => 30,
    ]);

    assertDatabaseHas('market_stocks', [
        'id'       => $this->marketStock->id,
        'quantity' => 70,
    ]);

});

it('should be not able to create a new withdraw if quantity greater than a market stock quantity', function () {

    expect($this->marketStock->quantity)
        ->toBe(100);

    // Act
    $lw = livewire(MarketStock\Withdrawals\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockWithdraw.market_stock_id', $this->marketStock->id)
        ->set('marketStockWithdraw.market_id', $this->market->id)
        ->set('marketStockWithdraw.price', 10)
        ->set('marketStockWithdraw.quantity', 101)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockWithdraw.quantity']);

    assertDatabaseMissing('market_stock_withdrawals', [
        'market_stock_id' => $this->marketStock->id,
        'market_id'       => $this->market->id,
        'price'           => 10,
        'quantity'        => 101,
    ]);

    assertDatabaseHas('market_stocks', [
        'id'       => $this->marketStock->id,
        'quantity' => 100,
    ]);

});

test('market stock id is required', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.market_stock_id', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_stock_id' => 'required']);

});

test('market stock id should be a integer', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.market_stock_id', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_stock_id' => 'integer']);
});

test('market stock id should be exists in to market stocks table', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.market_stock_id', 999)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_stock_id' => 'exists']);

});

test('market id is required', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.market_id', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_id' => 'required']);

});

test('market id should be a integer', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.market_id', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_id' => 'integer']);
});

test('market id should be exists in to markets table', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.market_id', 999)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.market_id' => 'exists']);

});

test('price is required', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.price', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.price' => 'required']);

});

test('price must be a numeric', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.price', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.price' => 'numeric']);

});

test('price must be greater than zero', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.price', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.price' => 'min']);

});

test('quantity is required', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.quantity', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.quantity' => 'required']);

});

test('quantity must be integer', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.quantity', 'test')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.quantity' => 'integer']);

});

test('quantity must be greater than zero', function () {

    // Arrange
    $lw = livewire(MarketStock\Entries\Create::class, ['marketStock' => $this->marketStock])
        ->set('marketStockEntry.quantity', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketStockEntry.quantity' => 'min']);

});
