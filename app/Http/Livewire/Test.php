<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Test extends Component
{
    public $count = 1;
    public function render()
    {
        return view('livewire.test');
    }
}
