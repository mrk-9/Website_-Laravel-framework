<?php

namespace App\Listeners;

use App\Events\AdPlacementWasShared;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailAdPlacementShare
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
     * @param  AdPlacementWasShared  $event
     * @return void
     */
    public function handle(AdPlacementWasShared $event)
    {
        $user = $event->user;
        $ad_placement = $event->ad_placement;
        $media = $ad_placement->media;
        $contact_email = $event->contact_email;
        $contact_message = $event->message;

        Mail::send('emails.ad-placement-share', compact('ad_placement', 'media', 'user', 'contact_message'), function($message) use ($contact_email) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($contact_email);
            $message->subject('Partage d\'offre MediaResa');
        });

        return true;
    }
}
