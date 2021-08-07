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

class FinishEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $content = [];
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
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
        $room = auth()->user()->room;
            $results = $Logic->judge($this->content );
            $results['message'] = '終了しました';
            $room->finish($results['winner']);
        return $results;
    }
}
