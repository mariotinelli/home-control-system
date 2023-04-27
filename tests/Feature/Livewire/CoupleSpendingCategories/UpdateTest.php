<?php

namespace Tests\Feature\Livewire\CoupleSpendingCategories;

use App\Http\Livewire\CoupleSpendingCategories;
use App\Models\{CoupleSpendingCategory, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->category = CoupleSpendingCategory::factory()->create();

    actingAs($this->user);
});

it('should be able to update a couple spending category', function () {

    // Act
    $lw = livewire(CoupleSpendingCategories\Update::class, ['category' => $this->category])
        ->set('category.name', 'Test Category Updated')
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('couple-spending-category::updated');

    assertDatabaseHas('couple_spending_categories', [
        'name' => 'Test Category Updated',
    ]);

});

test('name is required', function () {

    // Act
    $lw = livewire(CoupleSpendingCategories\Update::class, ['category' => $this->category])
        ->set('category.name', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'required']);

});

test('name must be a string', function () {

    // Act
    $lw = livewire(CoupleSpendingCategories\Update::class, ['category' => $this->category])
        ->set('category.name', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'string']);

});

test('name must be unique', function () {

    // Arrange
    $category = CoupleSpendingCategory::factory()->create();

    // Act
    $lw = livewire(CoupleSpendingCategories\Update::class, ['category' => $this->category])
        ->set('category.name', $category->name)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'unique']);

});

test('name must not be greater than 255 characters', function () {

    // Act
    $lw = livewire(CoupleSpendingCategories\Update::class, ['category' => $this->category])
        ->set('category.name', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'max']);

});
