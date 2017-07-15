<?php namespace App;

use App\BaseModel;
use Auth;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class AdPlacement extends BaseModel implements SluggableInterface
{
    use SoftDeletes, SluggableTrait;

    const TYPE_AUCTION = 'auction';
    const TYPE_OFFER = 'offer';
    const TYPE_BOOKING = 'booking';
    const TYPE_HYBRID = 'hybrid';

    const LOCK_DURATION = 30;

    private $now;

    protected $table = 'ad_placement';

    protected $sluggable = [
        'build_from' => 'name',
        'save_to'    => 'slug',
    ];

    protected $casts = [
        'price' => 'float',
        'minimum_price' => 'float',
        'user_max_price' => 'float',
        'user_min_price' => 'float',
        'deposit_percent' => 'float',
        'vat_rate' => 'float',
    ];

    protected $appends = [
        'user_min_price',
        'user_max_price',
        'starting_at_fr',
        'starting_at_fr_no_seconds',
        'ending_at_fr',
        'ending_at_fr_no_seconds',
        'broadcasting_date_fr',
        'broadcasting_date_fr_no_seconds',
        'locking_up_fr_no_seconds',
        'created_at_date',
        'technical_deadline_date',
        'technical_deadline_no_seconds',
        'time_before_closing',
        'finished',
        'type_fr',
        'deposit_percent',
        'vat_rate',
        'technical_doc'
    ];

    protected $sqlRequest = array();

    protected static $search_rules = [
        'media.name' => [
            'operator' => 'ILIKE',
            'value' => '%{value}%',
        ],
        'ad_placement.name' => [
            'operator' => 'ILIKE',
            'value' => '%{value}%',
        ],
        'format.name' => [
            'operator' => 'ILIKE',
            'value' => '%{value}%',
        ],
    ];

    protected $fillable = [
        'user_min_price',
        'user_max_price',
        'name',
        'description',
        'price',
        'starting_at',
        'ending_at',
        'type',
        'minimum_price',
        'edition',
        'technical_deadline',
        'position',
        'media_id',
        'lock_booking_id',
        'lock_ending_at',
        'locking_up',
        'broadcasting_date'
    ];

    public static function getTypesWithoutHybrid()
    {
        $basic_types = Collection::make(self::getTypes());
        $key = $basic_types->where('slug', 'hybrid')->keys()[0];
        $basic_types->forget($key);

        return $basic_types;
    }

    public static function getTypes()
    {
        return Collection::make(
            [
                ['slug' => self::TYPE_BOOKING, 'name' => 'Achat immédiat'],
                //['slug' => self::TYPE_OFFER, 'name' => 'Offre'],
                ['slug' => self::TYPE_AUCTION, 'name' => 'Enchère'],
                ['slug' => self::TYPE_HYBRID, 'name' => 'Achat immédiat et Offre']
            ]
        );
    }

    public function format()
    {
        return $this->belongsTo('App\Format');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function media()
    {
        return $this->belongsTo('App\Media');
    }

    public function selections()
    {
        return $this->hasMany('App\Selection');
    }

    public function offers()
    {
        return $this->hasMany('App\Offer');
    }

    public function auctions()
    {
        return $this->hasMany('App\Auction');
    }

    public function bookings()
    {
        return $this->hasMany('App\Booking');
    }

    public function acquisitions()
    {
        return $this->hasMany('App\Acquisition');
    }

    public function getUserMinPriceAttribute()
    {
        if ($this->type == self::TYPE_AUCTION) {
            if ($this->price === $this->minimum_price && $this->auctions->count() === 0) {
                return $this->minimum_price;
            }
            return $this->price + 1;
        }

        if ($this->type == self::TYPE_OFFER || $this->type == self::TYPE_HYBRID) {
            return $this->minimum_price;
        }
        return null;
    }

    public function getUserMaxPriceAttribute()
    {
        if ($this->type == self::TYPE_OFFER || $this->type == self::TYPE_HYBRID) {
            return $this->price - 1;
        }
        return null;
    }

    public function getStartingAtFrAttribute()
    {
        return Carbon::parse($this->starting_at)->format('d/m/Y H:i:s');
    }

    public function getStartingAtFrNoSecondsAttribute()
    {
        return Carbon::parse($this->starting_at)->format('d/m/Y H:i');
    }

    public function getEndingAtFrAttribute()
    {
        return Carbon::parse($this->ending_at)->format('d/m/Y H:i:s');
    }

    public function getEndingAtFrNoSecondsAttribute()
    {
        return Carbon::parse($this->ending_at)->format('d/m/Y H:i');
    }

    public function getTechnicalDeadlineDateAttribute()
    {
        if (is_null($this->technical_deadline)) {
            return '';
        }

        return Carbon::parse($this->technical_deadline)->format('d/m/Y');
    }

    public function getTechnicalDeadlineNoSecondsAttribute()
    {
        if (is_null($this->technical_deadline)) {
            return '';
        }

        return Carbon::parse($this->technical_deadline)->format('d/m/Y H:i');
    }

    public function getLockingUpFrNoSecondsAttribute()
    {
        if (is_null($this->locking_up)) {
            return '';
        }

        return Carbon::parse($this->locking_up)->format('d/m/Y H:i');
    }

    public function getBroadcastingDateFrAttribute()
    {
        if (is_null($this->broadcasting_date)) {
            return '';
        }

        return Carbon::parse($this->broadcasting_date)->format('d/m/Y');
    }

    public function getBroadcastingDateFrNoSecondsAttribute()
    {
        if (is_null($this->broadcasting_date)) {
            return '';
        }

        return Carbon::parse($this->broadcasting_date)->format('d/m/Y H:i');
    }

    public function getCreatedAtDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y');
    }

    public function getFinishedAttribute()
    {
        if (Carbon::parse($this->ending_at)->lt($this->getNow())) {
            return true;
        } else {
            if ($this->type != AdPlacement::TYPE_AUCTION && (isset($this->winner_offer) || isset($this->winner_booking))) {
                return true;
            }
            return false;
        }
    }

    public function getBoughtAttribute()
    {
        if (isset($this->winner_offer) || isset($this->winner_booking) || isset($this->winner_auction)) {
            return true;
        }
        return false;
    }

    public function getLockedAttribute()
    {
        if ($this->lock_ending_at != null && $this->getNow()->lt(Carbon::parse($this->lock_ending_at))) {
            return true;
        }
        return false;
    }

    public function getLockedByAttribute()
    {
        if ($this->getLockedAttribute()) {
            return $this->lock_booking_id;
        }
        return null;
    }

    public function lock(Booking $booking)
    {
        $this->lock_booking_id = $booking->id;
        $this->lock_ending_at = $this->getNow()->addMinutes(self::LOCK_DURATION);
        $this->save();
    }

    public function getWinnerOfferAttribute()
    {
        if (!array_key_exists('winner_offer', $this->sqlRequest)) {
            $this->sqlRequest['winner_offer'] = null;

            if (($this->type == AdPlacement::TYPE_OFFER || $this->type == AdPlacement::TYPE_HYBRID)) {
                $this->sqlRequest['winner_offer'] = Offer::where('offer.ad_placement_id', '=', $this->id)->whereNotNull('offer.ad_network_user_id')->first();
            }
        }

        return $this->sqlRequest['winner_offer'];
    }

    public function getUserOfferAttribute()
    {
        if (!array_key_exists('user_offer', $this->sqlRequest)) {
            $this->sqlRequest['user_offer'] = null;

            if (($this->type == AdPlacement::TYPE_OFFER || $this->type == AdPlacement::TYPE_HYBRID) && Auth::user()->check()) {
                $this->sqlRequest['user_offer'] = Offer::where('offer.ad_placement_id', $this->id)->where('offer.user_id', Auth::user()->get()->id)->first();
            }
        }

        return $this->sqlRequest['user_offer'];
    }

    public function getBookingReadyAttribute()
    {
        return !$this->finished && !$this->getLockedAttribute() && ($this->type == self::TYPE_BOOKING || $this->type == self::TYPE_HYBRID);
    }

    public function getAuctionReadyAttribute()
    {
        return (!$this->finished
            && $this->type == self::TYPE_AUCTION
            && (is_null($this->getUserAuctionAttribute()) || $this->getUserAuctionAttribute()->amount != $this->price)
        );
    }

    public function getOfferReadyAttribute()
    {
        return !$this->finished && ($this->type == self::TYPE_OFFER || $this->type == self::TYPE_HYBRID);
    }

    public function getWinnerBookingAttribute()
    {
        if (!array_key_exists('winner_booking', $this->sqlRequest)) {
            $this->sqlRequest['winner_booking'] = null;

            if ($this->type == AdPlacement::TYPE_BOOKING || $this->type == AdPlacement::TYPE_HYBRID) {
                $this->sqlRequest['winner_booking'] = Booking::select('booking.*')
                    ->leftJoin('acquisition', 'booking.ad_placement_id', '=', 'acquisition.ad_placement_id')
                    ->whereRaw('booking.user_id = acquisition.user_id')
                    ->where('booking.ad_placement_id', '=', $this->id)
                    ->where(function ($query) {
                        $query->whereNotNull('acquisition.charge_status')
                            ->orWhere('acquisition.transfer_status', Acquisition::TRANSFER_SUCCESS);
                    })->first();
            }
        }

        return $this->sqlRequest['winner_booking'];
    }

    public function getWinnerAuctionAttribute()
    {
        if (!array_key_exists('winner_auction', $this->sqlRequest)) {
            $this->sqlRequest['winner_auction'] = null;

            if ($this->type == AdPlacement::TYPE_AUCTION && Carbon::parse($this->ending_at)->lte($this->getNow())) {
                $this->sqlRequest['winner_auction'] = $this->getLastAuctionAttribute();
            }
        }

        return $this->sqlRequest['winner_auction'];
    }

    public function getLastAuctionAttribute()
    {
        if (!array_key_exists('last_auction', $this->sqlRequest)) {
            $this->sqlRequest['last_auction'] = null;

            $this->sqlRequest['last_auction'] = Auction::select('auction.*')
                ->leftJoin('ad_placement', 'auction.ad_placement_id', '=', 'ad_placement.id')
                ->where('auction.ad_placement_id', '=', $this->id)
                ->whereRaw('auction.amount = ad_placement.price')
                ->first();
        }

        return $this->sqlRequest['last_auction'];
    }

    public function getUserAuctionAttribute()
    {
        if (!array_key_exists('user_auction', $this->sqlRequest)) {
            $this->sqlRequest['user_auction'] = null;

            if ($this->type == AdPlacement::TYPE_AUCTION && Auth::user()->check()) {
                $this->sqlRequest['user_auction'] = Auction::select('auction.*')
                    ->leftJoin('acquisition', 'auction.ad_placement_id', '=', 'acquisition.ad_placement_id')
                    ->where('auction.ad_placement_id', $this->id)
                    ->where('auction.user_id', Auth::user()->get()->id)
                    ->first();
            }
        }

        return $this->sqlRequest['user_auction'];
    }

    public function getTimeBeforeClosingAttribute()
    {
        $now = $this->getNow();
        $end = Carbon::parse($this->ending_at);

        if ($now->lt($end)) {
            $days = $now->diffInDays($end);

            $now = $now->addDays($days);
            $hours = $now->diffInHours($end);

            $now = $now->addHours($hours);
            $minutes = $now->diffInMinutes($end);

            $daystxt = '';

            if ($days >= 1) {
                $daystxt = $days . ' j ';
            }

            $hourstxt = '';

            if ($hours >= 1) {
                $hourstxt = $hours . ' h ';
            }

            $minutestxt = '';

            if ($days == 0 && $minutes >= 1) {
                $minutestxt = ' et ' . $minutes . ' mn ';
            }

            return $daystxt . $hourstxt . $minutestxt;
        }

        return "Terminé";
    }

    public function depositPrice($price)
    {
        return round($price * $this->media->adNetwork->deposit_percent) / 100;
    }

    public function getDepositPercentAttribute()
    {
        return $this->media->adNetwork->deposit_percent;
    }

    public function getVatRateAttribute()
    {
        return env('VAT_RATE');
    }

    public function getTechnicalDocAttribute()
    {
        return $this->media->technical_doc_path;
    }

    public function getTypeFrAttribute()
    {
        return self::getTypes()->where('slug', $this->type)->first()['name'];
    }

    public function getWinnerAttribute()
    {
        if ($this->type === self::TYPE_HYBRID) {
            return is_null($this->winner_booking) ? $this->winner_offer : $this->winner_booking;
        } else if ($this->type === self::TYPE_BOOKING) {
            return $this->winner_booking;
        } else if ($this->type == self::TYPE_OFFER) {
            return $this->winner_offer;
        } else {
            return $this->winner_auction;
        }
    }

    public function getAcquisitionWinnerAttribute()
    {
        $winner = $this->getWinnerAttribute();

        if (is_null($winner)) {
            return null;
        }

        $ad_placement_id = $winner->ad_placement_id;

        $acquisition = Acquisition::select('acquisition.*')
            ->where('acquisition.ad_placement_id', $ad_placement_id)
            ->whereNotNull('invoice_id')
            ->first();

        $acquisition->load('invoice', 'technicalSupport', 'template');

        return $acquisition;
    }

    protected static function getBaseQuery()
    {
        $query = self::query()
            ->select('ad_placement.*')
            ->leftJoin('media', 'ad_placement.media_id', '=', 'media.id')
            ->leftJoin('format', 'ad_placement.format_id', '=', 'format.id');

        if (Auth::ad_network()->check()) {
            $query->where('media.ad_network_id', Auth::ad_network()->get()->ad_network_id);
        }

        return $query;
    }

    private function getNow()
    {
        if (is_null($this->now)) {
            $this->now = Carbon::now();
        }

        return clone $this->now;
    }

    /**
     * @param string $edition which represents the ad placement's edition
     */
    public function setEditionAttribute($edition)
    {
        $this->attributes['edition'] = empty($edition) ? null : $edition;
    }

    /**
     * @param string $minimum_price which represents the ad placement's minimum price
     */
    public function setMinimumPriceAttribute($minimum_price)
    {
        $this->attributes['minimum_price'] = empty($minimum_price) ? null : $minimum_price;
    }
}
