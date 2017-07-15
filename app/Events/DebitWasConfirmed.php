<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DebitWasConfirmed extends Event
{
    use SerializesModels;

    public $boughtItem;

    /**
     * Create a new event instance.
     *
     * @param Offer/Auction/Booking $boughtItem
     * @return void
     */
    public function __construct($boughtItem)
    {
        $this->boughtItem = $boughtItem;
    }
}
