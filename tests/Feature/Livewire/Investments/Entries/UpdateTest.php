<?php

namespace Tests\Feature\Livewire\Investments\Entries;

use App\Http\Livewire\Investments;
use App\Models\{Investment, InvestmentEntry, User};

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserSilverPermissions());

    $this->investment = Investment::factory()->create();

    $this->investmentEntry = InvestmentEntry::factory()->create([
        'investment_id' => $this->investment->id,
        'amount'        => 100,
    ]);

    actingAs($this->user);

});

it('should be to update an investment entry', function () {

    assertDatabaseCount('investment_entries', 1);

    // Act
    $lw = livewire(Investments\Entries\Update::class, [
        'investment'      => $this->investment,
        'investmentEntry' => $this->investmentEntry,
    ])
        ->set('investmentEntry.amount', 200)
        ->set('investmentEntry.date', '2021-01-10')
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('investment::entry::updated');

    assertDatabaseHas('investment_entries', [
        'investment_id' => $this->investment->id,
        'amount'        => 200,
        'date'          => '2021-01-10',
    ]);

});

test('amount is required', function () {

    // Act
    $lw = livewire(Investments\Entries\Update::class, [
        'investment'      => $this->investment,
        'investmentEntry' => $this->investmentEntry,
    ])
        ->set('investmentEntry.amount', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentEntry.amount' => 'required']);

});

test('amount is numeric', function () {

    // Act
    $lw = livewire(Investments\Entries\Update::class, [
        'investment'      => $this->investment,
        'investmentEntry' => $this->investmentEntry,
    ])
        ->set('investmentEntry.amount', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentEntry.amount' => 'numeric']);

});

test('amount is min 1', function () {

    // Act
    $lw = livewire(Investments\Entries\Update::class, [
        'investment'      => $this->investment,
        'investmentEntry' => $this->investmentEntry,
    ])
        ->set('investmentEntry.amount', 0)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentEntry.amount' => 'min']);

});

test('amount is max 1000', function () {

    // Act
    $lw = livewire(Investments\Entries\Update::class, [
        'investment'      => $this->investment,
        'investmentEntry' => $this->investmentEntry,
    ])
        ->set('investmentEntry.amount', 1001)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentEntry.amount' => 'max']);

});

test('date is required', function () {

    // Act
    $lw = livewire(Investments\Entries\Update::class, [
        'investment'      => $this->investment,
        'investmentEntry' => $this->investmentEntry,
    ])
        ->set('investmentEntry.date', null)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentEntry.date' => 'required']);

});

test('date is date', function () {

    // Act
    $lw = livewire(Investments\Entries\Update::class, [
        'investment'      => $this->investment,
        'investmentEntry' => $this->investmentEntry,
    ])
        ->set('investmentEntry.date', 'abc')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investmentEntry.date' => 'date']);

});
