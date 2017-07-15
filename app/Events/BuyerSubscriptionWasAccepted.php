<?php

namespace App\Events;

use App\User;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class BuyerSubscriptionWasAccepted extends Event
{
    use SerializesModels;

    public $referent;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $referent)
    {
        $this->referent = $referent;
    }
}
