<?php

namespace App\Events;

use App\Auction;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class AuctionWasWon extends Event
{
    use SerializesModels;

    public $auction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Auction $auction)
    {
        $this->auction = $auction;
    }
}
