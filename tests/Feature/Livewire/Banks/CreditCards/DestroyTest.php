<?php

namespace Tests\Feature\Livewire\CreditCards;

use App\Http\Livewire\CreditCards;
use App\Models\{CreditCard, Spending, User};
use Auth;
use function Pest\Laravel\{actingAs, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->givePermissionTo('credit_card_delete');

    $this->user->creditCards()->save(
        $this->creditCard = CreditCard::factory()->makeOne()
    );

    actingAs($this->user);
});

it('should be able delete a credit card', function () {

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Destroy::class, ['creditCard' => $this->creditCard])
        ->call('save')
        ->assertEmitted('credit-card::destroyed');

    // Assert
    assertDatabaseMissing('credit_cards', [
        'id' => $this->creditCard->id,
    ]);

});

it('should be not able delete a credit card if not has permission to this', function () {
    // Arrange
    $this->user->revokePermissionTo('credit_card_delete');

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Destroy::class, ['creditCard' => $this->creditCard])
        ->call('save')
        ->assertForbidden();

});

it('should be not able delete a credit card if not owner', function () {
    // Arrange
    $creditCard2 = CreditCard::factory()->create();

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Destroy::class, ['creditCard' => $creditCard2])
        ->call('save')
        ->assertForbidden();

});

it('should be not able delete a credit card if not authenticated', function () {

    // Arrange
    Auth::logout();

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Destroy::class, ['creditCard' => $this->creditCard])
        ->call('save')
        ->assertForbidden();

});

it('delete all spending when delete credit card', function () {
    // Arrange
    $this->creditCard->spendings()->saveMany(
        Spending::factory()->count(3)->make()
    );

    // Act
    livewire(\App\Http\Livewire\Banks\CreditCards\Destroy::class, ['creditCard' => $this->creditCard])
        ->call('save')
        ->assertEmitted('credit-card::destroyed');

    // Assert
    assertDatabaseMissing('credit_cards', [
        'id' => $this->creditCard->id,
    ]);

    assertDatabaseMissing('spendings', [
        'credit_card_id' => $this->creditCard->id,
    ]);

});
