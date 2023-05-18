<?php

namespace Tests\Feature\Livewire\BankAccounts\Entries;

use App\Http\Livewire\BankAccounts\Entries;
use App\Models\{BankAccount, BankAccountEntry, User};

use function Pest\Laravel\{actingAs, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('bank_account_entry_delete');

    $this->bankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    $this->bankAccount->entries()->save(
        $this->entry = BankAccountEntry::factory()->makeOne()
    );

    $this->bankAccount->update([
        'balance' => $this->bankAccount->balance + $this->entry->value,
    ]);

    actingAs($this->user);

});

it("should be able to delete a entry if are the account owner and has the 'bank_account_entry_delete' permission", function () {

    // Act
    livewire(Entries\Destroy::class, ['entry' => $this->entry])
        ->call('save')
        ->assertEmitted('bank-account::entry::deleted');

    // Assert
    assertDatabaseMissing('bank_account_entries', [
        'id' => $this->entry->id,
    ]);

    $newBalance = number_format($this->bankAccount->balance - $this->entry->value, 2, '.', '');

    expect($this->bankAccount->refresh())
        ->balance->toBe($newBalance);

});

it('should be not able to delete a entry if are not the account owner', function () {
    // Arrange
    $user2 = User::factory()->createOne();

    $user2->givePermissionTo('bank_account_entry_delete');

    // Act
    actingAs($user2);

    livewire(Entries\Destroy::class, ['entry' => $this->entry])
        ->call('save')
        ->assertForbidden();

});

it("should be not able to delete a entry if not has the 'bank_account_entry_delete' permission", function () {
    // Arrange
    $user2 = User::factory()->createOne();

    // Act
    actingAs($user2);

    livewire(Entries\Destroy::class, ['entry' => $this->entry])
        ->call('save')
        ->assertForbidden();

});
