<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ForElem extends Component
{
    public $num = 0;
    public $ary = [];
    public function add($i) {
        $this->ary[$i] = true;
    }
    public function render()
    {
        return view('livewire.for-elem');
    }
}
