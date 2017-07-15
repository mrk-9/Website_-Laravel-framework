<?php

namespace App\Listeners;

use App\Events\BuyerSubscriptionWasAccepted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class EmailBuyerSubscriptionAccepted
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
     * @param  BuyerSubscriptionWasDone  $event
     * @return false to stop the propagation
     */
    public function handle(BuyerSubscriptionWasAccepted $event)
    {
        $referent = $event->referent;

        Mail::send('emails.buyer-subscription-accepted', compact('referent'), function($message) use ($referent) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($referent->email);
            $message->subject('Votre inscription a été acceptée');
        });

        return true;
    }
}


