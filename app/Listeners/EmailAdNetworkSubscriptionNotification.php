<?php

namespace App\Listeners;

use App\Events\AdNetworkSubscriptionWasDone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailAdNetworkSubscriptionNotification
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
     * @param  AdNetworkSubscriptionWasDone  $event
     * @return void
     */
    public function handle(AdNetworkSubscriptionWasDone $event)
    {
        $referent = $event->referent;
        $ad_network = $event->ad_network;

        Mail::send('emails.ad-network-subscription', compact('referent', 'ad_network'), function($message) use ($referent) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($referent->email);
            $message->subject('Votre compte va être validé par notre équipe');
        });

        Mail::send('emails.ad-network-subscription', compact('referent', 'ad_network'), function($message) use ($ad_network) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($ad_network->email);
            $message->subject('Votre compte va être validé par notre équipe');
        });

        return true;
    }
}
