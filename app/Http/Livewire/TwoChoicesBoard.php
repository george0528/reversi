<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Board;

class TwoChoicesBoard extends Board
{
    public function render()
    {
        return view('livewire.two-choices-board');
    }
}
