<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use App\User;
use App\AdPlacement;

class AdPlacementWasShared extends Event
{
    use SerializesModels;

    public $ad_placement;
    public $user;
    public $contact_email;
    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AdPlacement $ad_placement, $user, $contact_email, $message)
    {
        $this->ad_placement = $ad_placement;
        $this->user = $user;
        $this->contact_email = $contact_email;
        $this->message = $message;
    }
}
