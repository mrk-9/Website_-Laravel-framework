<?php namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\BuyerSubscriptionWasDone' => [
            'App\Listeners\EmailBuyerSubscriptionConfirmation',
            'App\Listeners\EmailBuyerSubscriptionAlert',
        ],
        'App\Events\BuyerSubscriptionWasAccepted' => ['App\Listeners\EmailBuyerSubscriptionAccepted'],
        'App\Events\BuyerPasswordResetWasAsked' => ['App\Listeners\EmailBuyerPasswordResetProcess'],
        'App\Events\BuyerPasswordResetWasDone' => ['App\Listeners\EmailBuyerPasswordResetConfirmation'],
        'App\Events\AdNetworkPasswordResetWasAsked' => ['App\Listeners\EmailAdNetworkPasswordResetProcess'],
        'App\Events\AdNetworkPasswordResetWasDone' => ['App\Listeners\EmailAdNetworkPasswordResetConfirmation'],
        'App\Events\AdNetworkSubscriptionWasDone' => [
            'App\Listeners\EmailAdNetworkSubscriptionNotification',
            'App\Listeners\EmailAdNetworkSubscriptionAlert',
        ],
        'App\Events\BookingWasDone' => [
            'App\Listeners\EmailBookingNotification',
            'App\Listeners\EmailBookingConfirmation'
        ],
        'App\Events\OfferWasDone' => ['App\Listeners\EmailOfferNotification'],
        'App\Events\AuctionWasDone' => ['App\Listeners\EmailAuctionNotification'],
        'App\Events\OfferWasAccepted' => ['App\Listeners\EmailOfferConfirmation'],
        'App\Events\AuctionWasWon' => ['App\Listeners\EmailAuctionWonNotification'],
        'App\Events\OfferWasCanceled' => ['App\Listeners\EmailOfferCanceledNotification'],
        'App\Events\DebitWasConfirmed' => ['App\Listeners\EmailDebitNotification'],
        'App\Events\AdPlacementWasPublished' => ['App\Listeners\EmailAdPlacementPublication'],
        'App\Events\AdPlacementWasShared' => ['App\Listeners\EmailAdPlacementShare'],
        'App\Events\ContactSend' => ['App\Listeners\EmailContactSend'],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
