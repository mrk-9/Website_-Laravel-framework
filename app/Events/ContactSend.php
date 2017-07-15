<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class ContactSend extends Event
{
    use SerializesModels;

    public $lastname;
    public $firstname;
    public $email;
    public $phone;
    public $accountType;
    public $note;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($lastname, $firstname, $email, $phone, $accountType, $note)
    {
        $this->lastname = $lastname;
        $this->firstname = $firstname;
        $this->email = $email;
        $this->phone = $phone;
        $this->accountType = $accountType;
        $this->note = $note;
    }
}
