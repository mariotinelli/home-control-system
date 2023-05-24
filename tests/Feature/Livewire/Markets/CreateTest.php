<?php

namespace Tests\Feature\Livewire\Markets;

use App\Http\Livewire\Markets;
use App\Models\{Market, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserGoldPermissions());

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
        'user_id' => $this->user->id,
        'name'    => 'Test Market',
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
        'user_id' => $this->user->id,
        'name'    => 'Test Market',
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
        'user_id' => $this->user->id,
        'name'    => 'Test Market',
    ]);

});

it('should not be able to create a new market with a name that already exists', function () {

    // Arrange
    $this->user->markets()->save(
        $market = Market::factory()->create(
            [
                'name' => 'Test Market',
            ]
        )
    );

    // Act
    $lw = livewire(Markets\Create::class)
        ->set('market.name', $market->name)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['market.name' => 'unique']);

});

it('ignore unique name for different user', function () {

    // Arrange
    User::factory()->create()->markets()->save(
        $market = Market::factory()->create(
            [
                'name' => 'Test Market',
            ]
        )
    );

    // Act
    $lw = livewire(Markets\Create::class)
        ->set('market.name', $market->name)
        ->call('save');

    // Assert
    $lw->assertEmitted('market::created')
        ->assertHasNoErrors();

    assertDatabaseHas('markets', [
        'user_id' => $this->user->id,
        'name'    => 'Test Market',
    ]);

});

test('name should be a string', function () {

    // Act
    $lw = livewire(Markets\Create::class)
        ->set('market.name', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['market.name' => 'string']);

});
