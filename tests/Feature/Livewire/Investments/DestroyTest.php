<?php

namespace Tests\Feature\Livewire\Investments;

use App\Http\Livewire\Investments;
use App\Models\{Investment, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseMissing};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserSilverPermissions());

    $this->investment = Investment::factory()->create();

    actingAs($this->user);

});

it('should be able to force delete an investment if that doesnt have entries and withdrawals', function () {

    assertDatabaseHas('investments', ['id' => $this->investment->id]);

    livewire(Investments\Destroy::class, ['investment' => $this->investment])
        ->call('save')
        ->assertEmitted('investment::deleted');

    assertDatabaseMissing('investments', ['id' => $this->investment->id]);

});

it('should be able to disable an investment if that have entries', function () {

    $this->investment->entries()->create([
        'amount' => 100,
        'date'   => now()->format('Y-m-d'),
    ]);

    assertDatabaseHas('investments', [
        'id'         => $this->investment->id,
        'deleted_at' => null,
    ]);

    livewire(Investments\Destroy::class, ['investment' => $this->investment])
        ->call('save')
        ->assertEmitted('investment::deleted');

    expect($this->investment->refresh())
        ->deleted_at->not()->toBeNull;

});

it('should be able to disable an investment if that have withdrawals', function () {
    $this->investment->withdrawals()->create([
        'amount' => 100,
        'date'   => now()->format('Y-m-d'),
    ]);

    assertDatabaseHas('investments', [
        'id'         => $this->investment->id,
        'deleted_at' => null,
    ]);

    livewire(Investments\Destroy::class, ['investment' => $this->investment])
        ->call('save')
        ->assertEmitted('investment::deleted');

    expect($this->investment->refresh())
        ->deleted_at->not()->toBeNull;

});
