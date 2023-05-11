<?php

namespace Tests\Feature\Livewire\CoupleSpendingCategories;

use App\Http\Livewire\CoupleSpendingCategories;
use App\Models\User;

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserSilverPermissions());

    actingAs($this->user);

});

it('should be able to create a new couple spending category', function () {

    // Act
    $lw = livewire(CoupleSpendingCategories\Create::class)
        ->set('category.name', 'Test Category')
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('couple-spending-category::created');

    assertDatabaseHas('couple_spending_categories', [
        'name' => 'Test Category',
    ]);

});

test('name is required', function () {

    // Act
    $lw = livewire(CoupleSpendingCategories\Create::class)
        ->set('category.name', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'required']);

});

test('name must be a string', function () {

    // Act
    $lw = livewire(CoupleSpendingCategories\Create::class)
        ->set('category.name', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'string']);

});

test('name must be unique', function () {

    // Arrange
    $category = \App\Models\CoupleSpendingCategory::factory()->create();

    // Act
    $lw = livewire(CoupleSpendingCategories\Create::class)
        ->set('category.name', $category->name)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'unique']);

});

test('name must not be greater than 255 characters', function () {

    // Act
    $lw = livewire(CoupleSpendingCategories\Create::class)
        ->set('category.name', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['category.name' => 'max']);

});
