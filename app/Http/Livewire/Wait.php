<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Wait extends Component
{
    public $room_id;
    public function mount() {
        $this->room_id = auth()->user()->room_id;
    }
    public function getListeners() {
        return [
            "echo:laravel_database_private-match.{$this->room_id},RoomEvent" => 'join',
        ];
    }
    public function join() {
        return redirect()->route('onlineBattle');
    }
    public function render()
    {
        return view('livewire.wait');
    }
}
