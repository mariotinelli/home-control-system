<?php

namespace Tests\Feature\Livewire\Markets;

use App\Http\Livewire\Markets;
use App\Models\{Market, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserGoldPermissions());

    $this->market = Market::factory()->create([
        'name' => 'Test Market',
    ]);

    actingAs($this->user);

});

it('should be to update market', function () {

    // Act
    $lw = livewire(Markets\Update::class, ['market' => $this->market])
        ->set('market.name', 'Test Market Update')
        ->call('save');

    // Assert
    $lw->assertEmitted('market::updated')
        ->assertHasNoErrors();

    assertDatabaseHas('markets', [
        'name' => 'Test Market Update',
    ]);

});

it('name is required', function () {

    // Act
    $lw = livewire(Markets\Update::class, ['market' => $this->market])
        ->set('market.name', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['market.name' => 'required']);

});

it('should not be able to update market with a name longer than 255 characters', function () {

    // Act
    $lw = livewire(Markets\Update::class, ['market' => $this->market])
        ->set('market.name', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['market.name' => 'max']);

});

it('should not be able to update market with a name that already exists', function () {

    // Arrange
    $market = Market::factory()->create([
        'name' => 'Test Market Unique',
    ]);

    // Act
    $lw = livewire(Markets\Update::class, ['market' => $this->market])
        ->set('market.name', $market->name)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['market.name' => 'unique']);

});

it('should be able to update market with name current market', function () {

    // Act
    $lw = livewire(Markets\Update::class, ['market' => $this->market])
        ->set('market.name', $this->market->name)
        ->call('save');

    // Assert
    $lw->assertHasNoErrors();

});

test('name should be a string', function () {

    // Act
    $lw = livewire(Markets\Update::class, ['market' => $this->market])
        ->set('market.name', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['market.name' => 'string']);

});
