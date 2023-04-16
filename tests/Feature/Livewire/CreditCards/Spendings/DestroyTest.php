<?php

namespace Tests\Feature\Livewire\CreditCards\Spendings;

use App\Http\Livewire\CreditCards\Spendings;
use App\Models\CreditCard;
use App\Models\Spending;
use App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->creditCard = CreditCard::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $this->spending = Spending::factory()->create([
        'credit_card_id' => $this->creditCard->id,
    ]);

    $this->creditCard->update([
        'remaining_limit' => $this->creditCard->remaining_limit - $this->spending->amount,
    ]);

    actingAs($this->user);
});

it('should be able to delete a spending', function () {

    assertDatabaseHas('spendings', [
        'credit_card_id' => $this->creditCard->id,
        'amount' => $this->spending->amount,
        'description' => $this->spending->description,
    ]);

    assertDatabaseHas('credit_cards', [
        'id' => $this->creditCard->id,
        'remaining_limit' => $this->creditCard->remaining_limit,
    ]);

    livewire(Spendings\Destroy::class, ['spending' => $this->spending])
        ->call('destroy')
        ->assertEmitted('credit-card::spending::deleted');

    assertDatabaseMissing('spendings', [
        'credit_card_id' => $this->creditCard->id,
        'amount' => $this->spending->amount,
        'description' => $this->spending->description,
    ]);

    assertDatabaseHas('credit_cards', [
        'id' => $this->creditCard->id,
        'remaining_limit' => $this->creditCard->remaining_limit + $this->spending->amount,
    ]);
});

it('only credit card owner should be able to delete a spending', function () {

    actingAs(User::factory()->create());

    livewire(Spendings\Destroy::class, ['spending' => $this->spending])
        ->call('destroy')
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

    livewire(Spendings\Destroy::class, ['spending' => $this->spending])
        ->call('destroy')
        ->assertEmitted('credit-card::spending::deleted');

    assertDatabaseMissing('spendings', [
        'credit_card_id' => $this->creditCard->id,
        'amount' => $this->spending->amount,
        'description' => $this->spending->description,
    ]);

    assertDatabaseHas('credit_cards', [
        'id' => $this->creditCard->id,
        'remaining_limit' => $this->creditCard->remaining_limit + $this->spending->amount,
    ]);

});
