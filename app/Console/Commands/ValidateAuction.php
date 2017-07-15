<?php namespace App\Console\Commands;

use App\Acquisition;
use App\Auction;
use App\Events\AuctionWasWon;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Invoice;


class ValidateAuction extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'auction:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create invoice and capture stripe charge for the auction winning if it was paid with a credit card';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $acquisitions = Acquisition::where('acquisition.charge_status', '=', Acquisition::CHARGE_PENDING)
            ->leftjoin('ad_placement', 'ad_placement.id', '=', 'acquisition.ad_placement_id')
            ->where('ad_placement.ending_at', '<=', Carbon::now())
            ->leftjoin('auction', function ($join) {
                $join->on('auction.ad_placement_id', '=', 'ad_placement.id')
                    ->on('auction.user_id', '=', 'acquisition.user_id');
            })->whereRaw('auction.amount = ad_placement.price')->get();

        foreach ($acquisitions as $acquisition) {
            $charge = \Stripe\Charge::retrieve($acquisition->charge_id);
            $charge->capture();
            $acquisition->charge_status = Acquisition::CHARGE_SUCCESS;
            $invoice = Invoice::createFromAcquisition($acquisition);
            $acquisition->invoice_id = $invoice->id;
            $acquisition->save();
            $auction  = Auction::where('auction.ad_placement_id', $acquisition->ad_placement_id)
                ->where('auction.user_id', $acquisition->user_id)
                ->first();
            event(new AuctionWasWon($auction));
        }


    }

}
