<?php

namespace App\Listeners;

use App\Events\BuyerPasswordResetWasDone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailBuyerPasswordResetConfirmation
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
     * @param  BuyerPasswordResetWasDone  $event
     * @return void
     */
    public function handle(BuyerPasswordResetWasDone $event)
    {
        $referent = $event->referent;

        Mail::send('emails.password-reset-confirmation', compact('referent'), function($message) use ($referent) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($referent->email);
            $message->subject('Confirmation de réinitialisation de votre mot de passe');
        });

        return true;
    }
}
