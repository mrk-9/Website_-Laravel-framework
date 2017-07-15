<?php namespace App\Http\Controllers\Main\Account;

use App\Http\Controllers\Controller;
use App\AdPlacement;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Acquisition;
use DB;

class AdPlacementController extends Controller
{

    const PAGINATOR_NB_BY_PAGE = 10;

    private $now;

    private function getNow()
    {
        if ($this->now == null) {
            $this->now = Carbon::now();
        }
        return clone $this->now;
    }

    private function getBaseQuery()
    {
        return AdPlacement::with('media.support',
            'media.category',
            'media.targets',
            'media.theme',
            'media.broadcastingArea',
            'media.frequency',
            'format')->select('ad_placement.*')
            ->leftjoin('acquisition', function ($join) {
                $this->getBaseJoinQuery($join, 'acquisition');
            });
    }

    /*
     * $withThisUser = true : only this user
     * $withThisUser = false : all without this user
     */
    private function getBaseJoinQuery($join, $table, $withThisUser = true, $withoutNull = true)
    {
        $operator = '=';
        if ($withThisUser === false) {
            $operator = '<>';
        }
        $query = $join->on($table . '.ad_placement_id', '=', 'ad_placement.id')
            ->where($table . '.user_id', $operator, Auth::user()->get()->id);
        if($withoutNull) {
            return $query->whereNull($table . '.deleted_at');
        }
        return $query;
    }

    public function getAdPlacementsValid()
    {
        $query = $this->getBaseQuery()
            ->leftjoin('booking', function ($join) {
                $this->getBaseJoinQuery($join, 'booking');
            })
            ->leftjoin('auction', function ($join) {
                $this->getBaseJoinQuery($join, 'auction');
            })
            ->leftjoin('offer', function ($join) {
                $this->getBaseJoinQuery($join, 'offer');
            })
            // Booking
            ->where(function ($query) {
                $query->whereNotNull('booking.id')
                    ->where('acquisition.charge_status', '=', Acquisition::CHARGE_SUCCESS);
            })
            ->orWhere(function ($query) {
                $query->whereNotNull('booking.id')
                    ->where('acquisition.transfer_status', '=', Acquisition::TRANSFER_SUCCESS);
            })
            // Auction
            ->orWhere(function ($query) {
                $query->whereNotNull('auction.id')
                    ->where('ad_placement.ending_at', '<=', $this->getNow())
                    ->whereRaw('auction.amount = ad_placement.price');
            })
            // Offer
            ->orWhere(function ($query) {
                $query->whereNotNull('offer.id')
                    ->whereNotNull('offer.ad_network_user_id');
            });

        $paginator = $query->paginate(self::PAGINATOR_NB_BY_PAGE);

        return view('main.account.ad_placements_valid', compact('paginator'));
    }

    public function getAdPlacementsPending()
    {
        $query = $this->getBaseQuery()
            ->leftjoin('booking', function ($join) {
                $this->getBaseJoinQuery($join, 'booking');
            })
            ->leftjoin('auction', function ($join) {
                $this->getBaseJoinQuery($join, 'auction');
            })
            ->leftjoin('offer', function ($join) {
                $this->getBaseJoinQuery($join, 'offer');
            })
            ->leftjoin('offer as offer_without_this_user', function ($join) {
                $this->getBaseJoinQuery($join, 'offer_without_this_user', false)
                    ->whereNotNull('offer_without_this_user.ad_network_user_id');
            })
            // Booking
            ->where(function ($query) {
                $query->whereNotNull('booking.id')
                    ->where('acquisition.transfer_status', '=', Acquisition::TRANSFER_PENDING)
                    ->where('ad_placement.lock_ending_at', '>', $this->getNow())
                    ->whereRaw('ad_placement.lock_booking_id = booking.id');
            })
            // Auction
            ->orWhere(function ($query) {
                $query->whereNotNull('auction.id')
                    ->where('ad_placement.ending_at', '>', $this->getNow());
            })
            // Offer
            ->orWhere(function ($query) {
                $query->whereNotNull('offer.id')
                    ->whereNull('offer_without_this_user.ad_network_user_id')
                    ->whereNull('offer.ad_network_user_id');
            });

        $paginator = $query->paginate(self::PAGINATOR_NB_BY_PAGE);

        return view('main.account.ad_placements_pending', compact('paginator'));
    }

    public function getAdPlacementsCanceling()
    {
        $query = $this->getBaseQuery()
            ->leftjoin('booking', function ($join) {
                $this->getBaseJoinQuery($join, 'booking');
            })
            ->leftjoin('auction', function ($join) {
                $this->getBaseJoinQuery($join, 'auction');
            })
            ->leftjoin('offer', function ($join) {
                $this->getBaseJoinQuery($join, 'offer');
            })
            ->leftjoin('offer as last_deleted_offer', function ($join) {
                $this->getBaseJoinQuery($join, 'last_deleted_offer', true, false);
            })
            ->leftjoin('offer as offer_without_this_user', function ($join) {
                $this->getBaseJoinQuery($join, 'offer_without_this_user', false)
                    ->whereNotNull('offer_without_this_user.ad_network_user_id');
            })
            // Booking
            ->where(function ($query) {
                $query->whereNotNull('booking.id')
                    ->whereRaw('ad_placement.lock_booking_id <> booking.id');
            })
            ->orWhere(function ($query) {
                $query->whereNotNull('booking.id')
                    ->whereRaw('ad_placement.lock_booking_id = booking.id')
                    ->where('ad_placement.lock_ending_at', '<=', $this->getNow());
            })
            // Auction
            ->orWhere(function ($query) {
                $query->whereNotNull('auction.id')
                    ->where('ad_placement.ending_at', '<=', $this->getNow())
                    ->whereRaw('auction.amount <> ad_placement.price');
            })
            // Offer
            ->orWhere(function ($query) {
                $query->whereNotNull('offer.id')
                    ->whereNotNull('offer_without_this_user.ad_network_user_id')
                    ->whereNull('offer.ad_network_user_id');
            })
            ->orWhere(function ($query) {
                $query->whereNotNull('last_deleted_offer.id')
                    ->whereNull('offer.id');
            });

        $paginator = $query->paginate(self::PAGINATOR_NB_BY_PAGE);

        return view('main.account.ad_placements_canceling', compact('paginator'));
    }

    public function getAdPlacementsSelection()
    {
        $query = AdPlacement::select('ad_placement.*');
        $query->leftjoin('selection', 'selection.ad_placement_id', '=', 'ad_placement.id');
        $query->where('selection.user_id', Auth::user()->get()->id);
        $query->where('ad_placement.ending_at', '>', Carbon::now());
        $paginator = $query->paginate(self::PAGINATOR_NB_BY_PAGE);

        return view('main.account.ad_placements_selection', compact('paginator'));
    }

}
