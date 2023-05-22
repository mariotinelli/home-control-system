<?php

namespace Tests\Feature\Livewire\CreditCards\Spendings;

use App\Models\{CreditCard, Spending, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->givePermissionTo('credit_card_spending_update');

    $this->user->creditCards()->save(
        $this->creditCard = CreditCard::factory()->makeOne()
    );

    $this->creditCard->spendings()->save(
        $this->spending = Spending::factory()->makeOne([
            'amount' => $this->creditCard->remaining_limit,
        ])
    );

    $this->creditCard->update([
        'remaining_limit' => $this->creditCard->remaining_limit - $this->spending->amount,
    ]);

    actingAs($this->user);
});

it('should be able to update a spending', function () {

    // Arrange
    $newData = Spending::factory()->makeOne([
        'amount' => ($this->creditCard->remaining_limit + $this->spending->amount) - 1,
    ]);

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.amount', $newData->amount)
        ->set('spending.description', $newData->description)
        ->call('save')
        ->assertHasNoErrors()
        ->assertEmitted('credit-card::spending::updated');

    // Assert
    assertDatabaseHas('spendings', [
        'credit_card_id' => $this->creditCard->id,
        'amount'         => $newData->amount,
        'description'    => $newData->description,
    ]);

    assertDatabaseHas('credit_cards', [
        'id'              => $this->creditCard->id,
        'user_id'         => $this->user->id,
        'remaining_limit' => ($this->creditCard->remaining_limit + $this->spending->amount) - $newData->amount,
    ]);

});

it('should be not able to update spending amount to greater than remaining limit', function () {
    // Arrange
    $newData = Spending::factory()->makeOne([
        'amount' => ($this->creditCard->remaining_limit + $this->spending->amount) + 1,
    ]);

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.amount', $newData->amount)
        ->call('save')
        ->assertHasErrors(['spending.amount']);

});

it('should be not able to update a spending if not credit card owner', function () {
    // Arrange
    $creditCard2 = CreditCard::factory()->create();

    $creditCard2->spendings()->save(
        $spending2 = Spending::factory()->makeOne()
    );

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Update::class, ['spending' => $spending2])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to update a spending if not has permission to this', function () {
    // Arrange
    $this->user->revokePermissionTo('credit_card_spending_update');

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Update::class, ['spending' => $this->spending])
        ->call('save')
        ->assertForbidden();

});

it('should be not able to update a spending if not authenticated', function () {
    // Arrange
    \Auth::logout();

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Update::class, ['spending' => $this->spending])
        ->call('save')
        ->assertForbidden();

});

test('amount is required', function () {
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.amount', '')
        ->call('save')
        ->assertHasErrors(['spending.amount' => 'required']);
});

test('amount is numeric', function () {
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.amount', 'abc')
        ->call('save')
        ->assertHasErrors(['spending.amount' => 'numeric']);
});

test('amount should be have a max of 10 digits', function () {
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.amount', 12345678901)
        ->call('save')
        ->assertHasErrors(['spending.amount' => 'max_digits']);
});

test('description is required', function () {
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.description', '')
        ->call('save')
        ->assertHasErrors(['spending.description' => 'required']);
});

test('description is string', function () {
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.description', 123)
        ->call('save')
        ->assertHasErrors(['spending.description' => 'string']);
});

test('description should be have a max of 255 characters', function () {
    livewire(\App\Http\Livewire\Banks\CreditCards\Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.description', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['spending.description' => 'max']);
});
