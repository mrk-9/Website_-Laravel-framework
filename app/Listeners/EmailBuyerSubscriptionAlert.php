<?php

namespace App\Listeners;

use App\Events\BuyerSubscriptionWasDone;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;
use App\Admin;

class EmailBuyerSubscriptionAlert
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
    public function handle(BuyerSubscriptionWasDone $event)
    {
        $referent = $event->referent;
        $buyer = $event->buyer;
        $admins = Admin::all();
        $admin_emails = $admins->pluck('email');

        foreach ($admin_emails as $admin_email) {
            Mail::send('emails.buyer-subscription-alert', compact('referent', 'buyer'), function($message) use ($admin_email) {
                $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
                $message->to($admin_email);
                $message->subject('Inscription à valider');
            });
        }


        return true;
    }
}
