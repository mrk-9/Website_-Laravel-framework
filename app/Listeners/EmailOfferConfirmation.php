<?php

namespace App\Listeners;

use App\Events\OfferWasAccepted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailOfferConfirmation
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
     * @param  OfferWasAccepted  $event
     * @return void
     */
    public function handle(OfferWasAccepted $event)
    {
        $offer = $event->offer;
        $ad_placement = $offer->adPlacement;
        $media = $ad_placement->media;
        $buyer = $offer->user;

        Mail::send('emails.offer-confirmed', compact('offer', 'buyer', 'ad_placement', 'media'), function($message) use ($buyer) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($buyer->email);
            $message->subject('Votre offre a été acceptée');
        });

        return true;
    }
}
