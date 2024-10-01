<?php

namespace AccountHon\Events;

use AccountHon\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class KitchenOrder extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['kitchen-order-channel'];
    }
}
