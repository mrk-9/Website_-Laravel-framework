<?php

namespace App\Listeners;

use App\Events\AuctionWasWon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailAuctionWonNotification
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
     * @param  AuctionWasWon  $event
     * @return void
     */
    public function handle(AuctionWasWon $event)
    {
        $auction = $event->auction;
        $ad_placement = $auction->adPlacement;
        $media = $ad_placement->media;
        $user = $auction->user;

        Mail::send('emails.auction-won', compact('auction', 'ad_placement', 'media', 'user'), function($message) use ($user) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($user->email);
            $message->subject('Notification enchère gagnante');
        });

        return true;
    }
}
