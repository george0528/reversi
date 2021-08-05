<?php

namespace App\Http\Livewire;

use App\Models\Room;
use Livewire\Component;

class RoomList extends Component
{
    public $waitRooms;
    public function render()
    {
        $room = new Room;
        $this->waitRooms = $room->waitRooms();
        return view('livewire.room-list');
    }
}
