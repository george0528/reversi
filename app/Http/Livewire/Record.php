<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Board;

class Record extends Component
{
    public $user_id;
    public $board;
    public $count = 5;
    public $records;
    public function mount() {
        $this->user_id = auth()->user()->id;
        $this->get_records();
    }
    public function count_up() {
        $this->count += 5;
        $this->get_records();
    }
    public function get_records() {
        $board = new Board;
        $this->records = $board->orWhere('user1', $this->user_id)->orWhere('user2', $this->user_id)->get();
    }
    public function render()
    {
        return view('livewire.record');
    }
}
