<?php

namespace App\Listeners;

use App\Events\ContactSend;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class EmailContactSend
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ContactSend  $event
     * @return false to stop the propagation
     */
    public function handle(ContactSend $event)
    {
        $lastname = $event->lastname;
        $firstname = $event->firstname;
        $email = $event->email;
        $phone = $event->phone;
        $accountType = $event->accountType;
        $note = $event->note;

        Mail::send('emails.contact', compact('lastname', 'firstname', 'email', 'phone', 'accountType', 'note'), function($message) use ($email, $lastname, $firstname) {
            $message->from($email, $lastname . ' ' . $firstname);
            $message->to(env('MAIL_CONTACT'));
            $message->subject('Demande de contact');
        });

        return true;
    }
}
