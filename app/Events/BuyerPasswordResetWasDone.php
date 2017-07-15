<?php

namespace App\Events;

use App\Events\Event;
use App\User;
use Illuminate\Queue\SerializesModels;

class BuyerPasswordResetWasDone extends Event
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
