<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use App\Models\Menu;

class Sidebar extends Component
{
    public $menus;

    public function mount()
    {
        // ดึงเฉพาะเมนูที่ Active เรียงตาม Order
        $this->menus = Menu::where('is_active', true)->orderBy('order')->get();
    }

    public function render()
    {
        return view('livewire.layout.sidebar');
    }
}
