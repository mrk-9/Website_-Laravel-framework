<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acquisition extends BaseModel
{

	use SoftDeletes;

	const CHARGE_PENDING = 'pending';
	const CHARGE_SUCCESS = 'success';
	const TRANSFER_PENDING = 'pending';
	const TRANSFER_SUCCESS = 'success';

	protected $table = 'acquisition';

	protected $appends = ['created_at_date', 'total'];

	protected static $search_rules = [
		'media.name' => [
			'operator' => 'LIKE',
			'value' => '%{value}%',
		],
		'ad_placement.name' => [
			'operator' => 'LIKE',
			'value' => '%{value}%',
		],
	];

	protected $fillable = [
		'price',
		'technical_support_price',
		'charge_status',
		'transfer_status',
		'charge_id',
		'invoice_id',
		'user_id',
		'ad_placement_id',
		'technical_support_id',
		'template_id'
	];

	public function setVatRate()
	{
		$this->vat_rate = floatval(env("VAT_RATE"));
	}

	public function adPlacement()
	{
		return $this->belongsTo('App\AdPlacement');
	}

	public function invoice()
	{
		return $this->belongsTo('App\Invoice');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function getCreatedAtDateAttribute()
	{
		return Carbon::parse($this->created_at)->format('d/m/Y');
	}

	public function technicalSupport()
	{
		return $this->belongsTo('App\TechnicalSupport');
	}

	public function template()
	{
		return $this->belongsTo('App\Template');
	}

	public function getTotalAttribute()
	{
		return $this->getTotalWithoutVatAttribute() + $this->getVatPriceAttribute();
	}

	public function getTotalWithoutVatAttribute()
	{
		return $this->technical_support_price + $this->price;
	}

	public function getVatPriceAttribute()
	{
		return round(($this->getTotalWithoutVatAttribute()) * $this->vat_rate) / 100;
	}

	protected static function getBaseQuery()
	{
		$today = Carbon::now();

		return self::query()
			->selectRaw(
				'acquisition.*,
				media.name as media_name,
				ad_placement.name as ad_placement_name,
				ad_placement.edition,
				ad_placement.created_at as ad_placement_created_at,
				invoice.name'
			)->leftJoin('ad_placement', 'acquisition.ad_placement_id', '=', 'ad_placement.id')
			->leftJoin('media', 'ad_placement.media_id', '=', 'media.id')
			->leftJoin('invoice', 'acquisition.invoice_id', '=', 'invoice.id');
	}
}
