<?php

namespace Livewire\Banks\Accounts;

use App\Http\Livewire\Banks\Accounts\Index;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function the_component_can_render()
    {
        $component = Livewire::test(Index::class);

        $component->assertStatus(200);
    }
}
