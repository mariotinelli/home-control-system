<?php

namespace Tests\Feature\Livewire\CoupleSpendings;

use App\Http\Livewire\CoupleSpendings;
use App\Models\{CoupleSpending, CoupleSpendingCategory, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserSilverPermissions());

    $this->category = CoupleSpendingCategory::factory()->create();

    $this->coupleSpending = CoupleSpending::factory()->create([
        'couple_spending_category_id' => $this->category->id,
    ]);

    actingAs($this->user);

});

it('should be able to update a couple spending', function () {
    // Arrange
    $category2 = CoupleSpendingCategory::factory()->create();

    // Act
    $lw = livewire(CoupleSpendings\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.couple_spending_category_id', $category2->id)
        ->set('coupleSpending.description', 'Test Updated')
        ->set('coupleSpending.amount', 200)
        ->set('coupleSpending.date', '2023-01-01')
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('couple-spending::updated');

    assertDatabaseHas('couple_spendings', [
        'couple_spending_category_id' => $category2->id,
        'description'                 => 'Test Updated',
        'amount'                      => 200,
        'date'                        => '2023-01-01',
    ]);

});

test('couple spending category is required', function () {

    // Act
    $lw = livewire(CoupleSpendings\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.couple_spending_category_id', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.couple_spending_category_id' => 'required']);

});

test('couple spending category must exist', function () {

    // Act
    $lw = livewire(CoupleSpendings\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.couple_spending_category_id', 999)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.couple_spending_category_id' => 'exists']);

});

test('description is required', function () {

    // Act
    $lw = livewire(CoupleSpendings\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.description', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.description' => 'required']);

});

test('description must be a string', function () {

    // Act
    $lw = livewire(CoupleSpendings\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.description', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.description' => 'string']);

});

test('description cannot be more than 255 characters', function () {

    // Act
    $lw = livewire(CoupleSpendings\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.description', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.description' => 'max']);

});

test('amount is required', function () {

    // Act
    $lw = livewire(CoupleSpendings\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.amount', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.amount' => 'required']);

});

test('amount must be numeric', function () {

    // Act
    $lw = livewire(CoupleSpendings\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.amount', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.amount' => 'numeric']);

});

test('amount must be at least 1', function () {

    // Act
    $lw = livewire(CoupleSpendings\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.amount', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.amount' => 'min']);

});

test('date is required', function () {

    // Act
    $lw = livewire(CoupleSpendings\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.date', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.date' => 'required']);

});

test('date must be a date', function () {

    // Act
    $lw = livewire(CoupleSpendings\Update::class, ['coupleSpending' => $this->coupleSpending])
        ->set('coupleSpending.date', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.date' => 'date']);

});
