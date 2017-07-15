<?php

namespace App\Listeners;

use App\Events\BuyerSubscriptionWasDone;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class EmailBuyerSubscriptionConfirmation
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
     * @return true to allow the propagation
     */
    public function handle(BuyerSubscriptionWasDone $event)
    {
        $referent = $event->referent;

        Mail::send('emails.buyer-subscription', compact('referent'), function($message) use ($referent) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($referent->email);
            $message->subject('Confirmation d\'inscription à MediaResa');
        });

        return true;
    }
}
