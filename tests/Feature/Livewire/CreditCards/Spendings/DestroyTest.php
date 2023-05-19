<?php

namespace Tests\Feature\Livewire\CreditCards\Spendings;

use App\Http\Livewire\CreditCards\Spendings;
use App\Models\{CreditCard, Spending, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->givePermissionTo('credit_card_spending_delete');

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

it('should be able to delete a spending', function () {

    // Act
    livewire(Spendings\Destroy::class, ['spending' => $this->spending])
        ->call('destroy')
        ->assertEmitted('credit-card::spending::deleted');

    assertDatabaseMissing('spendings', [
        'credit_card_id' => $this->creditCard->id,
        'amount'         => $this->spending->amount,
        'description'    => $this->spending->description,
    ]);

    assertDatabaseHas('credit_cards', [
        'id'              => $this->creditCard->id,
        'remaining_limit' => $this->creditCard->remaining_limit + $this->spending->amount,
    ]);
});

it('should be not able to delete a spending if not credit card owner', function () {
    // Arrange
    $creditCard2 = CreditCard::factory()->create();

    $creditCard2->spendings()->save(
        $spending2 = Spending::factory()->makeOne()
    );

    // Act
    livewire(Spendings\Destroy::class, ['spending' => $spending2])
        ->call('destroy')
        ->assertForbidden();

});

it('should be not able to delete a spending if not has permission to this', function () {
    // Arrange
    $this->user->revokePermissionTo('credit_card_spending_delete');

    // Act
    livewire(Spendings\Destroy::class, ['spending' => $this->spending])
        ->call('destroy')
        ->assertForbidden();

});

it('should be not able to delete a spending if not authenticated', function () {
    // Arrange
    \Auth::logout();

    // Act
    livewire(Spendings\Destroy::class, ['spending' => $this->spending])
        ->call('destroy')
        ->assertForbidden();

});
