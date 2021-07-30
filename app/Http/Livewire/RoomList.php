<?php

namespace App\Http\Livewire;

use App\Models\Room;
use Livewire\Component;

class RoomList extends Component
{
    public $waitRooms;
    public function mount() {
        $room = new Room;
        $this->waitRooms = $room->waitRooms();
    }
    public function render()
    {
        return view('livewire.room-list');
    }
}
