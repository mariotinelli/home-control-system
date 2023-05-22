<?php

namespace Tests\Feature\Livewire\MarketItemCategories;

use App\Http\Livewire\MarketItemCategories;
use App\Models\User;
use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserGoldPermissions());

    actingAs($this->user);

});

it('should be able to create market item category', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Items\Categories\Create::class)
        ->set('marketItemCategory.name', 'Test Market Item Category')
        ->call('save');

    // Assert
    $lw->assertEmitted('market-item-category::created')
        ->assertHasNoErrors();

    assertDatabaseHas('market_item_categories', [
        'user_id' => $this->user->id,
        'name' => 'Test Market Item Category',
    ]);

});

it('name is required', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Items\Categories\Create::class)
        ->set('marketItemCategory.name', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItemCategory.name' => 'required']);

});

it('name is string', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Items\Categories\Create::class)
        ->set('marketItemCategory.name', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItemCategory.name' => 'string']);

});

it('name is max 100', function () {

    // Act
    $lw = livewire(\App\Http\Livewire\Markets\Items\Categories\Create::class)
        ->set('marketItemCategory.name', str_repeat('a', 101))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItemCategory.name' => 'max']);

});
