<?php

namespace App\Http\Livewire;

use App\Events\PublicEvent;
use App\Events\PutEvent;
use App\Http\Logic\LivewireLogic;
use App\Http\Logic\RequestLogic;
use App\Models\Room;
use Livewire\Component;

class Board extends Component
{
    public $puttedCoord = [];
    public $color;
    public $room_id;
    public $message;
    public $content;
    public $nexts;
    public $pass;
    public function mount()
    {
        $this->color = session('color');
        $this->room_id = session('room_id');

        // テスト用データ
        // $this->room_id = 1;
        $this->color = 1;

        $room = new Room;
        $room = $room->find($this->room_id);
        $this->content = $room->board->getContent();
    }
    // websocket
    public function getListeners() {
        return [
            "echo:laravel_database_private-battle.{$this->room_id},PutEvent" => 'enemy_putted',
            'nexts',
        ];
    }
    public function put($i1, $i2) {
        $this->puttedCoord = [$i1,$i2];
        $Logic = new LivewireLogic;
        $results = $Logic->put($i1, $i2, $this->color, $this->room_id);
        if(isset($results['problem'])) {
            return $this->message = 'そこには置けません';
        } else {
            $this->nexts = null;
            $this->pass = null;
            $this->content = $results['content'];
            broadcast(new PutEvent([$i1, $i2], $this->content))->toOthers();
        }
    }
    public function enemy_putted($data) {
        $this->content = $data['content'];
        $this->puttedCoord = $data['puttedCoord'];
        $this->emit('nexts');
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
