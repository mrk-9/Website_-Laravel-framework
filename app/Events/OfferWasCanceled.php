<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use App\AdPlacement;
use App\User;

class OfferWasCanceled extends Event
{
    use SerializesModels;

    public $adPlacement;
    public $buyer;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $buyer, AdPlacement $adPlacement)
    {
        $this->adPlacement = $adPlacement;
        $this->buyer = $buyer;
    }
}
