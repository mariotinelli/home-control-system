<?php

namespace Tests\Feature\Livewire\Markets;

use App\Http\Livewire\Markets;
use App\Models\Market;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    actingAs($this->user);

});

it('should be to create a new market', function () {

    // Act
    $lw = livewire(Markets\Create::class)
        ->set('market.name', 'Test Market')
        ->call('save');

    // Assert
    $lw->assertEmitted('market::created')
        ->assertHasNoErrors();

    assertDatabaseHas('markets', [
        'name' => 'Test Market'
    ]);

});

it('should not be able to create a new market without a name', function () {

    // Act
    $lw = livewire(Markets\Create::class)
        ->set('market.name', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['market.name' => 'required']);

    assertDatabaseMissing('markets', [
        'name' => 'Test Market'
    ]);

});

it('should not be able to create a new market with a name longer than 255 characters', function () {

    // Act
    $lw = livewire(Markets\Create::class)
        ->set('market.name', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['market.name' => 'max']);

    assertDatabaseMissing('markets', [
        'name' => 'Test Market'
    ]);

});

it('should not be able to create a new market with a name that already exists', function () {

    // Arrange
    $market = Market::factory()->create([
        'name' => 'Test Market'
    ]);

    // Act
    $lw = livewire(Markets\Create::class)
        ->set('market.name', $market->name)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['market.name' => 'unique']);

});

test('name should be a string', function () {

    // Act
    $lw = livewire(Markets\Create::class)
        ->set('market.name', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['market.name' => 'string']);

    assertDatabaseMissing('markets', [
        'name' => 'Test Market'
    ]);
});


