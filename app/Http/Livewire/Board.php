<?php

namespace App\Http\Livewire;

use App\Events\FinishEvent;
use App\Events\PassEvent;
use App\Events\PublicEvent;
use App\Events\PutEvent;
use App\Events\SurrenderEvent;
use App\Http\Logic\LivewireLogic;
use App\Http\Logic\RequestLogic;
use App\Models\Room;
use Livewire\Component;
use Livewire\Livewire;

class Board extends Component
{
    public $puttedCoord = [];
    public $color;
    public $room_id;
    public $message;
    public $content;
    public $nexts;
    public $pass;
    public $winner;
    public $next_color;
    public $finish_message;
    // リスナー(websocketを含む)
    public function getListeners() {
        return [
            "echo:laravel_database_private-battle.{$this->room_id},PutEvent" => 'enemy_putted',
            "echo:laravel_database_private-battle.{$this->room_id},PassEvent" => 'enemy_pass',
            "echo:laravel_database_private-battle.{$this->room_id},FinishEvent" => 'finish',
            "echo:laravel_database_private-battle.{$this->room_id},SurrenderEvent" => 'finish',
            // "echo-presence:presence.{$this->room_id},here" => 'presence_action',
            // "echo-presence:presence.{$this->room_id},joining" => 'presence_action',
            "echo-presence:presence.{$this->room_id},leaving" => 'enemy_leave',
            // "echo-presence:presence.{$this->room_id},listen, PresenceEvent" => 'p_listen',
            'put', // 消す
            'pass', // 消す
        ];
    }
    public function mount()
    {
        $user = auth()->user();
        $this->color = $user->color;
        $this->room_id = $user->room_id;
        $room = new Room;
        $room = $room->find($this->room_id);
        $this->content = $room->board->getContent();
        $this->next_color = $room->board->next_color;
        if($this->next_color == $this->color) {
            $this->nexts();
        }
    }
    public function put($i1, $i2) {
        $this->puttedCoord = [$i1,$i2];
        $Logic = new LivewireLogic;
        $results = $Logic->put($i1, $i2, $this->color, $this->room_id);
        if(isset($results['problem'])) {
            return $this->message = 'そこには置けません';
        } else {
            $this->data_reset();
            $this->content = $results['content'];
            broadcast(new PutEvent([$i1, $i2], $this->content))->toOthers();
            $this->turn_next_color();
            if($results['finish']) {
                broadcast(new FinishEvent($this->content));
            }
        }
    }
    public function pass() {
        $this->data_reset();
        $Logic = new LivewireLogic;
        $Logic->pass($this->color);
        broadcast(new PassEvent)->toOthers();
        $this->turn_next_color();
    }
    public function enemy_putted($data) {
        $this->content = $data['content'];
        $this->puttedCoord = $data['puttedCoord'];
        $this->turn_next_color();
        $this->nexts();
    }
    public function enemy_pass() {
        $this->turn_next_color();
        $this->nexts();
    }
    public function nexts() {
        $Logic = new LivewireLogic;
        $nexts = $Logic->nexts($this->color, $this->content);
        if(empty($nexts)) {
            $this->pass = true;
            // $this->emit('pass'); // 消す
        } else {
            $this->nexts = $nexts;
            $this->emit('put',$this->nexts[0][0], $this->nexts[0][1]); // 消す
        }
    }
    public function finish($data) {
        $this->next_color = $this->color;
        $this->winner = $data['winner'];
        $this->finish_message = $data['message'];
        if($this->winner == $this->color) {
            $board = auth()->user()->room->board;
            $board->winner = auth()->user()->id;
            $board->save();
        }
        $this->user_data_delete();
        $this->data_reset();
    }
    public function surrender() {
        broadcast(new SurrenderEvent);
    }
    public function finish_btn() {
        return redirect()->route('onlineList');
    }
    public function data_reset() {
        $this->reset(['message', 'nexts', 'pass']);
    }
    public function user_data_delete() {
        $user = auth()->user();
        $user->room_id = null;
        $user->color = null;
        $user->save();
    }
    public function turn_next_color() {
        $Logic = new RequestLogic;
        $this->next_color = $Logic->turnColor($this->next_color);
    }
    // プレゼンス チャンネル
    public function enemy_leave() {
        $this->finish([
            'winner' => $this->color,
            'message' => '敵が接続切れしました',
        ]);
    }
    public function render()
    {
        return view('livewire.board');
    }
}
