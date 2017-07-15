<?php

namespace App\Events;

use App\AdNetworkUser;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class AdNetworkPasswordResetWasAsked extends Event
{
    use SerializesModels;

    public $referent;
    public $reset_path;

    /**
     * Create a new event instance.
     *
     * @return void
     */
   public function __construct(AdNetworkUser $referent, $reset_path)
    {
        $this->referent = $referent;
        $this->reset_path = $reset_path;
    }
}
