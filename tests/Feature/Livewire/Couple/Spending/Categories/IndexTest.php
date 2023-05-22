<?php

namespace Livewire\Couple\Spending\Categories;

use App\Http\Livewire\Couple\Spending\Categories\Index;
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
