<?php

namespace App\Listeners;

use App\Events\OfferWasCanceled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailOfferCanceledNotification
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
     * @param  OfferWasCanceled  $event
     * @return void
     */
    public function handle(OfferWasCanceled $event)
    {
        $ad_placement = $event->adPlacement;
        $media = $ad_placement->media;
        $referent = $media->adNetwork->referent;
        $buyer = $event->buyer;

        Mail::send('emails.offer-canceled', compact('offer', 'buyer', 'ad_placement', 'media', 'referent'), function($message) use ($referent) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($referent->email);
            $message->subject('Notification d\'offre annulée');
        });

        return true;
    }
}
