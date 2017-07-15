<?php

namespace App\Listeners;

use App\Events\DebitWasConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class EmailDebitNotification
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
     * @param  DebitWasConfirmed  $event
     * @return void
     */
    public function handle(DebitWasConfirmed $event)
    {
        $boughtItem = $event->boughtItem;
        $ad_placement = $boughtItem->adPlacement;
        $media = $ad_placement->media;
        $user = $boughtItem->user;
        $price = $boughtItem->total;

        Mail::send('emails.bought-item-confirmation', compact('boughtItem', 'ad_placement', 'media', 'user', 'price'), function($message) use ($user) {
            $message->from(env('MAIL_CONTACT'), env('MAIL_NAME'));
            $message->to($user->email);
            $message->subject('Notification d\'un nouveau prélèvement.');
        });

        return true;
    }
}
