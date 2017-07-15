<?php

namespace App\Listeners;

use App\Events\AdNetworkPasswordResetWasAsked;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailAdNetworkPasswordResetProcess
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
     * @param  AdNetworkPasswordResetWasAsked  $event
     * @return void
     */
    public function handle(AdNetworkPasswordResetWasAsked $event)
    {
        $referent = $event->referent;
        $reset_path = $event->reset_path;

        Mail::send('emails.password-reset-process', compact('referent', 'reset_path'), function($message) use ($referent) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($referent->email);
            $message->subject('Votre demande de réinitialisation de mot de passe');
        });

        return true;
    }
}
