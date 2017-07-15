<?php

namespace App\Listeners;

use App\Admin;
use App\Events\AdNetworkSubscriptionWasDone;
use Mail;

class EmailAdNetworkSubscriptionAlert
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
     * @return false to stop the propagation
     */
    public function handle(AdNetworkSubscriptionWasDone $event)
    {
        $ad_network = $event->ad_network;
        $referent = $event->referent;

        $admin_emails = Admin::all()->pluck('email');

        foreach ($admin_emails as $admin_email) {
            Mail::send('emails.ad-network-subscription-alert', compact('ad_network', 'referent'), function ($message) use ($admin_email) {
                $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
                $message->to($admin_email);
                $message->subject('Nouvelle régie');
            });
        }

        return true;
    }
}
