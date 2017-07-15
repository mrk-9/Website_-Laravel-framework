<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use App\AdPlacement;

class AdPlacementWasPublished extends Event
{
    use SerializesModels;

    public $adPlacement;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AdPlacement $adPlacement)
    {
        $this->adPlacement = $adPlacement;
    }
}
