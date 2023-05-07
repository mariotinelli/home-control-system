<?php

namespace Tests\Feature\Livewire\Investments;

use App\Http\Livewire\Investments;
use App\Models\{Investment, User};

use function Pest\Laravel\{actingAs, assertDatabaseHas};
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create();

    $this->user->givePermissionTo(getUserSilverPermissions());

    $this->investment = Investment::factory()->create();

    actingAs($this->user);

});

it('should be able to create a new investments', function () {

    // Act
    $lw = livewire(Investments\Update::class, ['investment' => $this->investment])
        ->set('investment.name', 'Test Investment 2')
        ->set('investment.description', 'Test Description 2')
        ->set('investment.owner', 'Mário 2')
        ->set('investment.start_date', '2021-01-02')
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('investment::updated');

    assertDatabaseHas('investments', [
        'name'        => 'Test Investment 2',
        'description' => 'Test Description 2',
        'owner'       => 'Mário 2',
        'start_date'  => '2021-01-02',
    ]);

});

test('name is required', function () {

    // Act
    $lw = livewire(Investments\Update::class, ['investment' => $this->investment])
        ->set('investment.name', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.name' => 'required']);

});

test('name is unique', function () {

    // Arrange
    Investment::factory()->create([
        'name' => 'Test Investment',
    ]);

    // Act
    $lw = livewire(Investments\Update::class, ['investment' => $this->investment])
        ->set('investment.name', 'Test Investment')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.name' => 'unique']);

});

test('name should be have a max 255 characters', function () {

    // Act
    $lw = livewire(Investments\Update::class, ['investment' => $this->investment])
        ->set('investment.name', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.name' => 'max']);

});

test('name should be a string', function () {

    // Act
    $lw = livewire(Investments\Update::class, ['investment' => $this->investment])
        ->set('investment.name', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.name' => 'string']);

});

test('description is required', function () {

    // Act
    $lw = livewire(Investments\Update::class, ['investment' => $this->investment])
        ->set('investment.description', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.description' => 'required']);

});

test('description should be have a max 255 characters', function () {

    // Act
    $lw = livewire(Investments\Update::class, ['investment' => $this->investment])
        ->set('investment.description', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.description' => 'max']);

});

test('description should be a string', function () {

    // Act
    $lw = livewire(Investments\Update::class, ['investment' => $this->investment])
        ->set('investment.description', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.description' => 'string']);

});

test('owner is required', function () {

    // Act
    $lw = livewire(Investments\Update::class, ['investment' => $this->investment])
        ->set('investment.owner', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.owner' => 'required']);

});

test('owner should be have a max 255 characters', function () {

    // Act
    $lw = livewire(Investments\Update::class, ['investment' => $this->investment])
        ->set('investment.owner', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.owner' => 'max']);

});

test('owner should be a string', function () {

    // Act
    $lw = livewire(Investments\Update::class, ['investment' => $this->investment])
        ->set('investment.owner', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.owner' => 'string']);

});

test('start_date is required', function () {

    // Act
    $lw = livewire(Investments\Update::class, ['investment' => $this->investment])
        ->set('investment.start_date', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.start_date' => 'required']);

});

test('start_date is a date', function () {

    // Act
    $lw = livewire(Investments\Update::class, ['investment' => $this->investment])
        ->set('investment.start_date', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.start_date' => 'date']);

});
