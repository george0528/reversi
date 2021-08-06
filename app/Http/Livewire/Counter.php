<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $c = 0;
    public function inc() {
        $this->c++;
    }
    public function render()
    {
        return view('livewire.counter');
    }
}
