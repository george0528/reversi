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
    public $data = [];
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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
        if(isset($this->data['flag'])) {
            $results['winner'] = $this->data['winner'];
            $results['count'] = null;
            $results['message'] = $this->data['message'];
            $room->finish($this->data['winner']);
        } else {
            $results = $Logic->judge($this->data['content']);
            $results['message'] = '終了しました';
            $room->finish($results['winner']);
        }
        return $results;
    }
}
