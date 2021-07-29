<?php

namespace App\Http\Livewire;

use App\Http\Logic\LivewireLogic;
use App\Http\Logic\RequestLogic;
use App\Models\Room;
use Livewire\Component;

class Board extends Component
{
    public $i1 = 0;
    public $i2 = 0;
    public $color;
    public $room_id;
    public $message;
    public $content;
    public $nexts;
    public $pass;
    public $listeners = ['nexts'];
    public function __construct()
    {
        $this->color = session('color');
        $this->room_id = session('room_id');

        // テスト用データ
        $this->room_id = 1;
        $this->color = 1;


        $room = new Room;
        $room = $room->find($this->room_id);
        $this->content = $room->board->getContent();
    }
    public function put($i1, $i2) {
        $this->i1 = $i1;
        $this->i2 = $i2;
        $Logic = new LivewireLogic;
        $results = $Logic->put($this->i1, $this->i2, $this->color, $this->room_id);
        if(isset($results['problem'])) {
            return $this->message = 'そこには置けません';
        } else {
            $this->nexts = null;
            $this->pass = null;
            $this->content = $results['content'];
        }
    }
    public function nexts() {
        $Logic = new LivewireLogic;
        $nexts = $Logic->nexts($this->color, $this->content);
        if(empty($nexts)) {
            $this->pass = true;
        } else {
            $this->nexts = $nexts;
        }
    }
    public function render()
    {
        return view('livewire.board');
    }
}
