<?php

namespace Tests\Feature\Livewire\CreditCards\Spendings;

use App\Http\Livewire\CreditCards\Spendings;
use App\Models\CreditCard;
use App\Models\Spending;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->creditCard = CreditCard::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->oldRemainingLimit = $this->creditCard->remaining_limit;

    $this->spending = Spending::factory()->create([
        'credit_card_id' => $this->creditCard->id,
    ]);

    $this->creditCard->update([
        'remaining_limit' => $this->creditCard->remaining_limit - $this->spending->amount,
    ]);

    actingAs($this->user);
});

it('should be able to update a spending', function () {

    assertDatabaseHas('spendings', [
        'credit_card_id' => $this->creditCard->id,
        'amount' => $this->spending->amount,
        'description' => $this->spending->description,
    ]);

    assertDatabaseHas('credit_cards', [
        'id' => $this->creditCard->id,
        'remaining_limit' => $this->creditCard->remaining_limit,
    ]);

    livewire(Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.amount', 500)
        ->set('spending.description', 'Test Update')
        ->call('save')
        ->assertEmitted('credit-card::spending::updated');

    assertDatabaseHas('spendings', [
        'credit_card_id' => $this->creditCard->id,
        'amount' => 500,
        'description' => 'Test Update',
    ]);

    assertDatabaseHas('credit_cards', [
        'id' => $this->creditCard->id,
        'remaining_limit' => $this->oldRemainingLimit - 500,
    ]);

});

it('only credit card owner should be able to update a spending', function () {

    actingAs(User::factory()->create());

    livewire(Spendings\Update::class, ['spending' => $this->spending])
        ->call('save')
        ->assertForbidden();

    assertDatabaseHas('spendings', [
        'credit_card_id' => $this->creditCard->id,
        'amount' => $this->spending->amount,
        'description' => $this->spending->description,
    ]);

    assertDatabaseHas('credit_cards', [
        'id' => $this->creditCard->id,
        'remaining_limit' => $this->creditCard->remaining_limit,
    ]);

    actingAs($this->user);

    livewire(Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.amount', 500)
        ->set('spending.description', 'Test Update')
        ->call('save')
        ->assertEmitted('credit-card::spending::updated');

    assertDatabaseHas('spendings', [
        'credit_card_id' => $this->creditCard->id,
        'amount' => 500,
        'description' => 'Test Update',
    ]);

    assertDatabaseHas('credit_cards', [
        'id' => $this->creditCard->id,
        'remaining_limit' => $this->oldRemainingLimit - 500,
    ]);

});

test('amount is required', function () {
    livewire(Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.amount', '')
        ->call('save')
        ->assertHasErrors(['spending.amount' => 'required']);
});

test('amount is numeric', function () {
    livewire(Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.amount', 'abc')
        ->call('save')
        ->assertHasErrors(['spending.amount' => 'numeric']);
});

test('amount should be have a max of 10 digits', function () {
    livewire(Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.amount', 12345678901)
        ->call('save')
        ->assertHasErrors(['spending.amount' => 'max_digits']);
});

test('description is required', function () {
    livewire(Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.description', '')
        ->call('save')
        ->assertHasErrors(['spending.description' => 'required']);
});

test('description is string', function () {
    livewire(Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.description', 123)
        ->call('save')
        ->assertHasErrors(['spending.description' => 'string']);
});

test('description should be have a max of 255 characters', function () {
    livewire(Spendings\Update::class, ['spending' => $this->spending])
        ->set('spending.description', str_repeat('a', 256))
        ->call('save')
        ->assertHasErrors(['spending.description' => 'max']);
});
