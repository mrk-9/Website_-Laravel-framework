<?php

namespace App\Events;

use App\Events\Event;
use App\User;
use Illuminate\Queue\SerializesModels;

class BuyerPasswordResetWasAsked extends Event
{
    use SerializesModels;

    public $referent;
    public $reset_path;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $referent, $reset_path)
    {
        $this->referent = $referent;
        $this->reset_path = $reset_path;
    }
}
