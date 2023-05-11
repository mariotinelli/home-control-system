<?php

namespace Tests\Feature\Livewire\CoupleSpendings;

use App\Http\Livewire\CoupleSpendings;
use App\Models\{CoupleSpendingCategory, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserSilverPermissions());

    $this->category = CoupleSpendingCategory::factory()->create();

    actingAs($this->user);

});

it('should be able to create couple spending', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.couple_spending_category_id', $this->category->id)
        ->set('coupleSpending.description', 'Test')
        ->set('coupleSpending.amount', 100)
        ->set('coupleSpending.date', '2021-01-01')
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('couple-spending::created');

    assertDatabaseHas('couple_spendings', [
        'couple_spending_category_id' => $this->category->id,
        'description'                 => 'Test',
        'amount'                      => 100,
        'date'                        => '2021-01-01',
    ]);

});

test('couple spending category is required', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.couple_spending_category_id', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.couple_spending_category_id' => 'required']);

});

test('couple spending category must exist', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.couple_spending_category_id', 999)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.couple_spending_category_id' => 'exists']);

});

test('description is required', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.description', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.description' => 'required']);

});

test('description must be a string', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.description', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.description' => 'string']);

});

test('description cannot be more than 255 characters', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.description', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.description' => 'max']);

});

test('amount is required', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.amount', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.amount' => 'required']);

});

test('amount must be numeric', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.amount', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.amount' => 'numeric']);

});

test('amount must be at least 1', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.amount', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.amount' => 'min']);

});

test('date is required', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.date', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.date' => 'required']);

});

test('date must be a date', function () {

    // Act
    $lw = livewire(CoupleSpendings\Create::class)
        ->set('coupleSpending.date', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['coupleSpending.date' => 'date']);

});
