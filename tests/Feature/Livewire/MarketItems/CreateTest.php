<?php

namespace Tests\Feature\Livewire\MarketItems;

use App\Enums\TypeOfWeightEnum;
use App\Http\Livewire\MarketItems;
use App\Models\{MarketItem, MarketItemCategory, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->marketItemCategory = MarketItemCategory::factory()->create([
        'name' => 'Test Category',
    ]);

    actingAs($this->user);

});

it('should be able to create market item', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.name', 'Test Market Item')
        ->set('marketItem.market_item_category_id', $this->marketItemCategory->id)
        ->set('marketItem.type_weight', TypeOfWeightEnum::GRAM)
        ->set('marketItem.weight', 10)
        ->call('save');

    // Assert
    $lw->assertEmitted('market-item::created')
        ->assertHasNoErrors();

    assertDatabaseHas('market_items', [
        'name'                    => 'Test Market Item',
        'market_item_category_id' => $this->marketItemCategory->id,
        'type_weight'             => TypeOfWeightEnum::GRAM,
        'weight'                  => 10,
    ]);

});

it('unique name for market item, but ignore if market item category is different', function () {

    // Arrange
    $marketItemCategory2 = MarketItemCategory::factory()->create([
        'name' => 'Test Category 2',
    ]);

    $marketItem2 = MarketItem::factory()->create([
        'market_item_category_id' => $marketItemCategory2->id,
    ]);

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.name', $marketItem2->name)
        ->set('marketItem.market_item_category_id', $marketItemCategory2->id)
        ->set('marketItem.type_weight', TypeOfWeightEnum::GRAM)
        ->set('marketItem.weight', 10)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.name' => 'unique']);

    // Act 2
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.name', $marketItem2->name)
        ->set('marketItem.market_item_category_id', $this->marketItemCategory->id)
        ->set('marketItem.type_weight', TypeOfWeightEnum::GRAM)
        ->set('marketItem.weight', 10)
        ->call('save');

    // Assert 2
    $lw->assertEmitted('market-item::created')
        ->assertHasNoErrors();

    assertDatabaseHas('market_items', [
        'name'                    => $marketItem2->name,
        'market_item_category_id' => $this->marketItemCategory->id,
        'type_weight'             => TypeOfWeightEnum::GRAM,
        'weight'                  => 10,
    ]);

});

test('name is required', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.name', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.name' => 'required']);

});

test('name is string', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.name', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.name' => 'string']);

});

test('name is max 150', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.name', str_repeat('a', 151))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.name' => 'max']);

});

test('market_item_category_id is required', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.market_item_category_id', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.market_item_category_id' => 'required']);

});

test('market_item_category_id is integer', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.market_item_category_id', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.market_item_category_id' => 'integer']);

});

test('market_item_category_id is exists', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.market_item_category_id', 999)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.market_item_category_id' => 'exists']);

});

test('type_weight is required', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.type_weight', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.type_weight' => 'required']);

});

test('type_weight is string', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.type_weight', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.type_weight' => 'string']);

});

test('type_weight is in TypeOfWeightEnum', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.type_weight', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.type_weight' => 'in']);

});

test('weight is required', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.weight', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.weight' => 'required']);

});

test('weight is numeric', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.weight', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.weight' => 'numeric']);

});

test('weight is min 1', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.weight', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.weight' => 'min']);

});

test('weight is max 100000', function () {

    // Act
    $lw = livewire(MarketItems\Create::class)
        ->set('marketItem.weight', 1000000)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['marketItem.weight' => 'max']);

});
