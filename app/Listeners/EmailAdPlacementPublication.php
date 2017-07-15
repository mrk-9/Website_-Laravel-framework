<?php

namespace App\Listeners;

use App\Events\AdPlacementWasPublished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailAdPlacementPublication
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
     * @param  AdPlacementWasPublished  $event
     * @return void
     */
    public function handle(AdPlacementWasPublished $event)
    {
        $ad_placement = $event->adPlacement;
        $media = $ad_placement->media;
        $referent = $media->adNetwork->referent;

        Mail::send('emails.ad-placement-published-confirmation', compact('ad_placement', 'media', 'referent'), function ($message) use ($referent, $media) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to(is_null($referent) ? $media->adNetwork->email : $referent->email);
            $message->subject('Votre emplacement a été publié');
        });

        return true;
    }
}
