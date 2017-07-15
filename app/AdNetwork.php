<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;

class AdNetwork extends BaseModel implements SluggableInterface
{
	use SoftDeletes, SluggableTrait;

	const STATUS_PENDING = 'pending';
    const STATUS_VALID = 'valid';

	protected $table = 'ad_network';

	protected $sluggable = [
		'build_from' => 'name',
		'save_to' => 'slug',
	];

	protected $fillable = [
		'name',
		'corporate_name',
		'company_type',
		'address',
		'address2',
		'zipcode',
		'city',
		'phone',
		'email',
		'status',
		'supports',
		'ad_network_user_id',
		'deposit_percent'
	];

	protected static $search_rules = [
        'name' => [
            'operator' => 'ILIKE',
            'value' => '%{value}%'
        ],
        'email' => [
            'operator' => 'ILIKE',
            'value' => '%{value}%'
        ],
    ];

	protected static function boot()
	{
		parent::boot();

		self::creating(function (AdNetwork $adNetwork) {
			$adNetwork->deposit_percent = env('DEPOSIT_PERCENT');
		});
	}

	public function referent()
	{
		return $this->belongsTo('App\AdNetworkUser', 'ad_network_user_id');
	}

	public function adNetworkUsers()
	{
		return $this->hasMany('App\AdNetworkUser');
	}

	public function getStatusFrAttribute()
	{
		switch ($this->status) {
			case $this::STATUS_VALID:
				return "validé";
			case $this::STATUS_PENDING:
				return "en attente";
		}
	}
}
