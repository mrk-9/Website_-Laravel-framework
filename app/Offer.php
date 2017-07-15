<?php namespace App;

use App\Events\DebitWasConfirmed;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends BaseModel
{

	use SoftDeletes;

	const PUBLICATION_PENDING = 'publication_pending';
	const PUBLICATION_VALID = 'publication_valid';

	protected $table = 'offer';

	protected $sqlRequest = array();

	protected $fillable = [
		'publication_status',
		'order',
		'user_id',
		'ad_placement_id',
		'admin_id',
		'ad_network_user_id',
		'amount',
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
	];

	protected $appends = ['created_at_date'];

	public function getCreatedAtDateAttribute()
	{
		return Carbon::parse($this->created_at)->format('d/m/Y');
	}

	public function adPlacement()
	{
		return $this->belongsTo('App\AdPlacement');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function admin()
	{
		return $this->belongsTo('App\Admin');
	}

	public function adNetworkUser()
	{
		return $this->belongsTo('App\AdNetworkUser');
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

	public function getIsReadyToBeAcceptedAttribute()
	{
		$this->adPlacement->append(['locked_by', 'winner_offer']);

		return (is_null($this->adPlacement->locked_by) && is_null($this->adPlacement->winner_offer));
	}

	public function accept()
	{
		if($this->is_ready_to_be_accepted) {
			$ad_network_user_id = $this->adPlacement->media->adNetwork->referent->id;
			$this->ad_network_user_id = $ad_network_user_id;
			if($this->acquisition()->charge_status === Acquisition::CHARGE_PENDING) {
				$charge = \Stripe\Charge::retrieve($this->acquisition()->charge_id);
				$charge->capture();
				$this->acquisition()->charge_status = Acquisition::CHARGE_SUCCESS;
				$invoice = Invoice::createFromAcquisition($this->acquisition());
				$this->acquisition()->invoice_id = $invoice->id;
				$this->acquisition()->save();
			}
			if($this->acquisition()->charge_status === Acquisition::TRANSFER_PENDING) {
				//TODO please send transfer
			}
			return $this->save();
		}

		return false;
	}

	public function getIsAcceptedAttribute()
	{
		return !(is_null($this->adNetworkUser));
	}

	public function getPaymentIsPendingAttribute()
	{
		return ($this->is_winner && $this->acquisition()->transfer_status == Acquisition::TRANSFER_PENDING);
	}

	public function getPaymentIsValidAttribute()
	{
		$transfer_status = $this->acquisition()->transfer_status;
		$charge_status = $this->acquisition()->charge_status;

		return ($transfer_status == Acquisition::TRANSFER_SUCCESS) || ($charge_status == Acquisition::CHARGE_SUCCESS);
	}

	public function getIsLostAttribute()
	{
		$this->adPlacement->append('bought');

		return (!$this->is_winner && $this->adPlacement->bought);
	}

	public function getIsReadyForTransferValidationAttribute()
	{
		return ($this->is_accepted && $this->payment_is_pending);
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
		return ($this->payment_is_valid && $this->publication_status === Offer::PUBLICATION_PENDING);
	}

	public function validatePublication()
	{
		if ($this->is_ready_for_publication) {
			$this->publication_status = Offer::PUBLICATION_VALID;

			return $this->save();
		}

		return false;
	}

	public function getIsWinnerAttribute()
	{
		$this->adPlacement->append('winner_offer');

		if (is_null($this->adPlacement->winner_offer)) {
			return false;
		}

		if ($this->adPlacement->winner_offer->id === $this->id) {
			return true;
		}

		return false;
	}

	protected static function getBaseQuery()
	{
		$query = self::query()
			->selectRaw(
				'offer.*,
				media.name as media_name,
				ad_placement.name as ad_placement_name,
				ad_placement.edition,
				ad_placement.created_at as ad_placement_created_at,
				acquisition.charge_status,
				acquisition.transfer_status'
			)->leftJoin('ad_placement', 'offer.ad_placement_id', '=', 'ad_placement.id')
			->leftJoin('media', 'ad_placement.media_id', '=', 'media.id')
			->leftJoin('user', 'offer.user_id', '=', 'user.id')
			->leftJoin('buyer', 'user.buyer_id', '=', 'buyer.id')
			->leftJoin('acquisition', function($join) {
				$join->on('acquisition.ad_placement_id', '=', 'ad_placement.id')
					->on('acquisition.user_id', '=', 'offer.user_id');
			})
			->whereNull('acquisition.deleted_at');

		if (Auth::ad_network()->check()) {
            $query->where('media.ad_network_id', Auth::ad_network()->get()->ad_network_id);
        }

        return $query;
	}
}
