<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use App\Auction;
use App\User;

class AuctionWasDone extends Event
{
    use SerializesModels;

    public $auction;
    public $last_buyer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Auction $auction, User $last_buyer)
    {
        $this->auction = $auction;
        $this->last_buyer = $last_buyer;
    }
}
