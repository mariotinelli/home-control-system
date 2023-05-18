<?php

namespace Tests\Feature\Livewire\BankAccounts;

use App\Http\Livewire\BankAccounts;
use App\Models\{BankAccount, User};

use function Pest\Laravel\{actingAs, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->createOne([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo('bank_account_delete');

    $this->bankAccount = BankAccount::factory()->createOne([
        'user_id' => $this->user->id,
    ]);

    actingAs($this->user);

});

it('should be able to destroy a bank account', function () {

    // Act
    livewire(BankAccounts\Destroy::class, ['bankAccount' => $this->bankAccount])
        ->call('destroy')
        ->assertEmitted('bank-account::destroyed');

    // Assert
    assertDatabaseMissing('bank_accounts', [
        'id' => $this->bankAccount->id,
    ]);

});

it('should be not able to delete a bank account if not own it', function () {

    // Arrange
    $user2 = User::factory()->createOne();

    $user2->givePermissionTo('bank_account_delete');

    // Act
    actingAs($user2);

    livewire(BankAccounts\Destroy::class, ['bankAccount' => $this->bankAccount])
        ->call('destroy')
        ->assertForbidden();
});

it('should be able to delete a bank account if own it', function () {

    // Act
    livewire(BankAccounts\Destroy::class, ['bankAccount' => $this->bankAccount])
        ->call('destroy')
        ->assertEmitted('bank-account::destroyed');

    // Assert
    assertDatabaseMissing('bank_accounts', [
        'id' => $this->bankAccount->id,
    ]);

});

it("should be able to delete a bank account if has the 'bank_account_delete' permission", function () {
    // Arrange
    $this->user->revokePermissionTo('bank_account_delete');

    // Act
    livewire(BankAccounts\Destroy::class, ['bankAccount' => $this->bankAccount])
        ->call('destroy')
        ->assertForbidden();
});

it("should be not able to delete a bank account if not has the 'bank_account_delete' permission", function () {

    // Act
    livewire(BankAccounts\Destroy::class, ['bankAccount' => $this->bankAccount])
        ->call('destroy')
        ->assertEmitted('bank-account::destroyed');

    // Assert
    assertDatabaseMissing('bank_accounts', [
        'id' => $this->bankAccount->id,
    ]);

});

it('delete all entries and withdrawals', function () {
    // Arrange
    $this->bankAccount->entries()->createMany([
        [
            'description' => 'Teste 1',
            'value'       => 100,
            'date'        => now(),
        ],
        [
            'description' => 'Teste 2',
            'value'       => 100,
            'date'        => now(),
        ],
    ]);

    $this->bankAccount->withdrawals()->createMany([
        [
            'description' => 'Teste 1',
            'value'       => 100,
            'date'        => now(),
        ],
        [
            'description' => 'Teste 2',
            'value'       => 100,
            'date'        => now(),
        ],
    ]);

    expect($this->bankAccount->entries()->count())->toBe(2)
        ->and($this->bankAccount->withdrawals()->count())->toBe(2);

    // Act
    livewire(BankAccounts\Destroy::class, ['bankAccount' => $this->bankAccount])
        ->call('destroy')
        ->assertEmitted('bank-account::destroyed');

    // Assert
    assertDatabaseMissing('bank_accounts', [
        'id' => $this->bankAccount->id,
    ]);

    assertDatabaseMissing('bank_account_entries', [
        'bank_account_id' => $this->bankAccount->id,
    ]);

    assertDatabaseMissing('bank_account_withdraws', [
        'bank_account_id' => $this->bankAccount->id,
    ]);
});
