<?php

namespace App\Events;

use App\Events\Event;
use App\AdNetworkUser;
use Illuminate\Queue\SerializesModels;

class AdNetworkPasswordResetWasDone extends Event
{
    use SerializesModels;

    public $referet;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AdNetworkUser $referent)
    {
        $this->referent = $referent;
    }
}
