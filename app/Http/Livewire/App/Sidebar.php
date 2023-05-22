<?php

namespace App\Http\Livewire\App;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class Sidebar extends Component
{
    public function render(): View
    {
        $json = File::json(public_path('menus.json'));

        $menus = json_decode(json_encode($json));

        foreach ($menus as $menu) {
            $menu = $menu->isGroup
                ? $this->checkActiveForGroup($menu)
                : $this->checkActiveForItem($menu);
        }

        return view('livewire.app.sidebar', compact('menus'));
    }

    private function checkActiveForGroup($menu)
    {
        foreach ($menu->group as $item) {
            if ($item->route == request()->route()->getName()) {
                $item->isActive   = true;
                $menu->isExpanded = true;
            }
        }

        return $menu;
    }

    private function checkActiveForItem($item)
    {
        if ($item->route == request()->route()->getName()) {
            $item->isActive = true;
        }

        return $item;
    }

}
