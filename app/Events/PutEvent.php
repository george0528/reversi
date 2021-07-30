<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PutEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $puttedCoord = [];
    public $content;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($puttedCoord, $content)
    {
        $this->puttedCoord = $puttedCoord;
        $this->content = $content;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $room_id = session('room_id');
        return new PrivateChannel('battle.'.$room_id);
    }
    public function broadcastWith() {
        return [
            'message' => '相手が置きました',
            'puttedCoord' => $this->puttedCoord,
            'content' => $this->content,
        ];
    }
}
