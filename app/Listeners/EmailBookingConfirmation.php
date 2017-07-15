<?php

namespace App\Listeners;

use App\Events\BookingWasDone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailBookingConfirmation
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
        $user = $booking->user;

        Mail::send('emails.booking-confirmation', compact('booking', 'ad_placement', 'media', 'user'), function($message) use ($user) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($user->email);
            $message->subject('Confirmation de votre réservation');
        });

        // propagation
        return true;
    }
}
