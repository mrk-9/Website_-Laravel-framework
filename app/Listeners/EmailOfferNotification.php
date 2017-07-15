<?php

namespace App\Listeners;

use App\Events\OfferWasDone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailOfferNotification
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
     * @param  OfferWasDone  $event
     * @return void
     */
    public function handle(OfferWasDone $event)
    {
        $offer = $event->offer;
        $ad_placement = $offer->adPlacement;
        $media = $ad_placement->media;
        $referent = $media->adNetwork->referent;
        $user = $offer->user;

        Mail::send('emails.offer-purchased', compact('offer', 'ad_placement', 'media', 'referent', 'user'), function($message) use ($referent) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($referent->email);
            $message->subject('Notification d\'une nouvelle offre');
        });

        return true;
    }
}
