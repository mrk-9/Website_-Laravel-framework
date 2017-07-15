<?php namespace App;

use App\Events\DebitWasConfirmed;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends BaseModel
{

	use SoftDeletes;

	const PUBLICATION_PENDING = 'publication_pending';
	const PUBLICATION_VALID = 'publication_valid';

	protected $table = 'booking';

	protected $appends = ['created_at_date'];

	protected $sqlRequest = array();

	protected $fillable = [
		'publication_status',
		'order',
		'user_id',
		'ad_placement_id',
		'admin_id',
	];

	protected static $search_rules = [
		'media.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
		'ad_placement.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
		'user.name' => [
			'raw' => 'CONCAT("user"."name", \' \', "user"."family_name", \' \', "user"."name") ILIKE \'%{value}%\'',
		],
		'buyer.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
		'payment_status' => [
			'raw' => '(acquisition.transfer_status = \'{value}\' or acquisition.charge_status = \'{value}\')',
		],
	];

	/**
	 * Admin who confirmed the order of booking
	 */
	public function admin()
	{
		return $this->belongsTo('App\Admin');
	}

	public function acquisition()
	{
		if (!array_key_exists('acquisition', $this->sqlRequest)) {
			$this->sqlRequest['acquisition'] = Acquisition::where('acquisition.ad_placement_id', $this->ad_placement_id)
				->where('acquisition.user_id', $this->user_id)
				->first();
		}

		return $this->sqlRequest['acquisition'];
	}

	public function getPaymentIsValidAttribute()
	{
		$transfer_status = $this->acquisition()->transfer_status;
		$charge_status = $this->acquisition()->charge_status;

		return ($transfer_status == Acquisition::TRANSFER_SUCCESS) || ($charge_status == Acquisition::CHARGE_SUCCESS);
	}

	public function getIsLostAttribute()
	{
		$transfer_status = $this->acquisition()->transfer_status;
		$this->adPlacement->append('locked');

		// To know if booking is canceled we just need to check if lock is over and transfer status is pending.
		// We cannot have a pending charge status during a booking with hybrid ad placements. Because booking always win.
		return ($transfer_status == Acquisition::TRANSFER_PENDING && !$this->adPlacement->locked);
	}

	public function getPaymentIsPendingAttribute()
	{
		$this->adPlacement->append('locked_by');

		if($this->adPlacement->locked_by === $this->id
			&& $this->acquisition()->transfer_status == Acquisition::TRANSFER_PENDING) {
			return true;
		}
		return false;
	}

	public function getIsReadyForTransferValidationAttribute()
	{
		return $this->payment_is_pending;
	}

	public function validateTransfer()
	{
		if($this->is_ready_for_transfer_validation) {
			$this->acquisition()->transfer_status = Acquisition::TRANSFER_SUCCESS;
			$invoice = Invoice::createFromAcquisition($this->acquisition());
			$this->acquisition()->invoice_id = $invoice->id;
			$this->acquisition()->save();
			event(new DebitWasConfirmed($this->acquisition()));
			return $this->acquisition();
		}
		return false;
	}

	public function getIsReadyForPublicationAttribute()
	{
		return ($this->payment_is_valid && $this->publication_status === Booking::PUBLICATION_PENDING);
	}

	public function validatePublication()
	{
		if ($this->is_ready_for_publication) {
			$this->publication_status = Booking::PUBLICATION_VALID;

			return $this->save();
		}
		return false;
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function adPlacement()
	{
		return $this->belongsTo('App\AdPlacement');
	}

	public function getCreatedAtDateAttribute()
	{
		return Carbon::parse($this->created_at)->format('d/m/Y');
	}

	/**
	 * Get bookings accepted by ad network user
	 *
	 * @param QueryBuilder $scopeQuery
	 */
	public function scopeAccepted($scopeQuery)
	{
		$scopeQuery->where(function ($query) {
			$query->where('ad_network_user_id', 'NOT', DB::raw('NULL'));
		});
	}

	/**
	 * Get bookings with order confirmed.
	 * Bookings should be accepted before.
	 *
	 * @param QueryBuilder $scopeQuery
	 */
	public function scopeConfirmed($scopeQuery)
	{
		$this->scopeisAccepted($scopeQuery)->where(function ($query) {
			$query->where('admin_id', 'NOT', DB::raw('NULL'));
		});
	}

	/**
	 * Get bookings published.
	 * Bookings should be accepted and confirmed.
	 *
	 * @param QueryBuilder $scopeQuery [description]
	 */
	public function scopePublished($scopeQuery)
	{
		$this->scopeOrderConfirmed($scopeQuery)->where(function ($query) {
			$query->where('publication_status', self::PUBLICATION_VALID);
		});
	}

	public function getIsWinnerAttribute()
	{
		$this->adPlacement->append('winner_booking');

		if (is_null($this->adPlacement->winner_booking)) {
			return false;
		}

		if ($this->adPlacement->winner_booking->id === $this->id) {
			return true;
		}

		return false;
	}

	protected static function getBaseQuery()
	{
		$query = self::query()
			->selectRaw(
				'booking.*,
				media.name as media_name,
				ad_placement.name as ad_placement_name,
				ad_placement.edition,
				ad_placement.created_at as ad_placement_created_at,
				acquisition.charge_status,
				acquisition.transfer_status'
			)->leftJoin('ad_placement', 'booking.ad_placement_id', '=', 'ad_placement.id')
			->leftJoin('media', 'ad_placement.media_id', '=', 'media.id')
			->leftJoin('user', 'booking.user_id', '=', 'user.id')
			->leftJoin('buyer', 'user.buyer_id', '=', 'buyer.id')
			->leftJoin('acquisition', function($join) {
				$join->on('acquisition.ad_placement_id', '=', 'ad_placement.id')
					->on('acquisition.user_id', '=', 'booking.user_id');
			})
			->whereNull('acquisition.deleted_at');

		if (Auth::ad_network()->check()) {
            $query->where('media.ad_network_id', Auth::ad_network()->get()->ad_network_id);
        }

        return $query;
	}
}
