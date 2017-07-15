<?php

namespace App\Listeners;

use App\Events\BookingWasDone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailBookingNotification
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
     * @param  BookingWasDone  $event
     * @return void
     */
    public function handle(BookingWasDone $event)
    {
        $booking = $event->booking;
        $ad_placement = $booking->adPlacement;
        $media = $ad_placement->media;
        $referent = $media->adNetwork->referent;
        $user = $booking->user;

        Mail::send('emails.booking-purchased', compact('booking', 'ad_placement', 'media', 'referent', 'user'), function($message) use ($referent) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($referent->email);
            $message->subject('Notification d\'une nouvelle réservation');
        });
        // propagation
        return true;
    }
}
