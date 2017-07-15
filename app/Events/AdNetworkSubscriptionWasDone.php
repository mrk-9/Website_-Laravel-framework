<?php

namespace App\Events;

use App\AdNetwork;
use App\AdNetworkUser;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class AdNetworkSubscriptionWasDone extends Event
{
    use SerializesModels;

    public $ad_network;
    public $referent;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AdNetwork $ad_network, AdNetworkUser $referent)
    {
        $this->ad_network = $ad_network;
        $this->referent = $referent;
    }
}
