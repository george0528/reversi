<?php

namespace App\Events;

use App\Http\Logic\RequestLogic;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SurrenderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $room = auth()->user()->room;
        $room->is_battle = 0;
        $room->save();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $room_id = auth()->user()->room_id;
        return new PrivateChannel('battle.'.$room_id);
    }
    public function broadcastWith() {
        $Logic = new RequestLogic;
        $surrender_color = auth()->user()->color;
        $winner_color = $Logic->turnColor($surrender_color);
        return [
            'winner' => $winner_color,
            'message' => '投了しました',
        ];
    }
}
