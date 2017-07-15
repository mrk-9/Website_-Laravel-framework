<?php

namespace App\Listeners;

use App\Events\AuctionWasDone;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailAuctionNotification
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
     * @param  AuctionWasDone  $event
     * @return void
     */
     public function handle(AuctionWasDone $event)
    {
        $auction = $event->auction;
        $last_buyer = $event->last_buyer;
        $ad_placement = $auction->adPlacement;
        $media = $ad_placement->media;
        // $referent = $media->adNetwork->referent;
        // $user = $auction->user;

        Mail::send('emails.auction-purchased', compact('auction', 'ad_placement', 'media', 'user', 'last_buyer'), function($message) use ($last_buyer) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($last_buyer->email);
            $message->subject('Notification d\'une nouvelle enchère');
        });

        return true;
    }
}
