<?php

namespace App\Events;

use App\Buyer;
use App\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class BuyerSubscriptionWasDone extends Event
{
    use SerializesModels;

    public $buyer;
    public $referent;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Buyer $buyer, User $referent)
    {
        $this->buyer = $buyer;
        $this->referent = $referent;
    }
}
