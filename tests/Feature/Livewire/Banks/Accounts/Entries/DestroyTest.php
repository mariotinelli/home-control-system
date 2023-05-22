<?php

namespace Tests\Feature\Livewire\BankAccounts\Entries;

use App\Models\{BankAccount, BankAccountEntry, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
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

it("should be able to delete a entry", function () {

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Destroy::class, ['entry' => $this->entry])
        ->call('save')
        ->assertEmitted('bank-account::entry::deleted');

    // Assert
    assertDatabaseMissing('bank_account_entries', [
        'id' => $this->entry->id,
    ]);

    assertDatabaseHas('bank_accounts', [
        'id'      => $this->bankAccount->id,
        'balance' => $this->bankAccount->balance - $this->entry->value,
    ]);

});

it('should be not able to delete a entry if are not the account owner', function () {
    // Arrange
    $bankAccount2 = BankAccount::factory()->createOne();

    $bankAccount2->entries()->save(
        $entry2 = BankAccountEntry::factory()->makeOne()
    );

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Destroy::class, ['entry' => $entry2])
        ->call('save')
        ->assertForbidden();

});

it("should be not able to delete a entry if not has permission to this", function () {
    // Arrange
    $this->user->revokePermissionTo('bank_account_entry_delete');

    // Act
    livewire(\App\Http\Livewire\Banks\Accounts\Entries\Destroy::class, ['entry' => $this->entry])
        ->call('save')
        ->assertForbidden();

});
