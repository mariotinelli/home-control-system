<?php

namespace Tests\Feature\Livewire\CreditCards;

use App\Http\Livewire\CreditCards;
use App\Models\{CreditCard, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserPermissions());

    $this->creditCard = CreditCard::factory()->create([
        'user_id' => $this->user->id,
    ]);

    actingAs($this->user);
});

it('should be able delete a credit card', function () {

    assertDatabaseHas('credit_cards', [
        'id' => $this->creditCard->id,
    ]);

    livewire(CreditCards\Destroy::class, ['creditCard' => $this->creditCard])
        ->call('save')
        ->assertEmitted('credit-card::destroyed');

    assertDatabaseMissing('credit_cards', [
        'id' => $this->creditCard->id,
    ]);
});

it('should be able to delete a credit card only the card owner', function () {

    assertDatabaseHas('credit_cards', [
        'id' => $this->creditCard->id,
    ]);

    actingAs(User::factory()->create());

    livewire(CreditCards\Destroy::class, ['creditCard' => $this->creditCard])
        ->call('save')
        ->assertForbidden();

    assertDatabaseHas('credit_cards', [
        'id' => $this->creditCard->id,
    ]);

    actingAs($this->user);

    livewire(CreditCards\Destroy::class, ['creditCard' => $this->creditCard])
        ->call('save')
        ->assertEmitted('credit-card::destroyed');

    assertDatabaseMissing('credit_cards', [
        'id' => $this->creditCard->id,
    ]);
});
