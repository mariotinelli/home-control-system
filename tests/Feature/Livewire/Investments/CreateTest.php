<?php

namespace Tests\Feature\Livewire\Investments;

use App\Http\Livewire\Investments;
use App\Models\{Investment, User};

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

beforeEach(function () {

    $this->user = User::factory()->create([
        'email' => 'teste@email.com',
    ]);

    $this->user->givePermissionTo(getUserSilverPermissions());

    $this->actingAs($this->user);

});

it('should be able to create a new investments', function () {

    // Act
    $lw = livewire(Investments\Create::class)
        ->set('investment.name', 'Test Investment')
        ->set('investment.description', 'Test Description')
        ->set('investment.owner', 'Mário')
        ->set('investment.start_date', '2021-01-01')
        ->call('save');

    // Assert
    $lw->assertHasNoErrors()
        ->assertEmitted('investment::created');

    assertDatabaseHas('investments', [
        'name'        => 'Test Investment',
        'description' => 'Test Description',
        'owner'       => 'Mário',
        'start_date'  => '2021-01-01',
    ]);

});

test('name is required', function () {

    // Act
    $lw = livewire(Investments\Create::class)
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
    $lw = livewire(Investments\Create::class)
        ->set('investment.name', 'Test Investment')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.name' => 'unique']);

});

test('name should be have a max 255 characters', function () {

    // Act
    $lw = livewire(Investments\Create::class)
        ->set('investment.name', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.name' => 'max']);

});

test('name should be a string', function () {

    // Act
    $lw = livewire(Investments\Create::class)
        ->set('investment.name', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.name' => 'string']);

});

test('description is required', function () {

    // Act
    $lw = livewire(Investments\Create::class)
        ->set('investment.description', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.description' => 'required']);

});

test('description should be have a max 255 characters', function () {

    // Act
    $lw = livewire(Investments\Create::class)
        ->set('investment.description', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.description' => 'max']);

});

test('description should be a string', function () {

    // Act
    $lw = livewire(Investments\Create::class)
        ->set('investment.description', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.description' => 'string']);

});

test('owner is required', function () {

    // Act
    $lw = livewire(Investments\Create::class)
        ->set('investment.owner', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.owner' => 'required']);

});

test('owner should be have a max 255 characters', function () {

    // Act
    $lw = livewire(Investments\Create::class)
        ->set('investment.owner', str_repeat('a', 256))
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.owner' => 'max']);

});

test('owner should be a string', function () {

    // Act
    $lw = livewire(Investments\Create::class)
        ->set('investment.owner', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.owner' => 'string']);

});

test('start_date is required', function () {

    // Act
    $lw = livewire(Investments\Create::class)
        ->set('investment.start_date', '')
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.start_date' => 'required']);

});

test('start_date is a date', function () {

    // Act
    $lw = livewire(Investments\Create::class)
        ->set('investment.start_date', 123)
        ->call('save');

    // Assert
    $lw->assertHasErrors(['investment.start_date' => 'date']);

});
