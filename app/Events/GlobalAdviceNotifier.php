<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GlobalAdviceNotifier extends Event implements ShouldBroadcast
{
    use SerializesModels;

    protected $data;

    protected $eventName;

    public function __construct( $data , $event )
    {
        $this->data = $data;

        $this->eventName = 'advice:' . $event;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['advice:update'];
    }

    public function broadcastAs()
    {
        return $this->eventName;
    }

    public function broadcastWith()
    {
        return $this->data->toArray();
    }
}
