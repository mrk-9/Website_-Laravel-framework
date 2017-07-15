<?php namespace App;

use Auth;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends BaseModel implements SluggableInterface
{

	use SoftDeletes, SluggableTrait;

	protected $table = 'media';

	protected $sluggable = [
        'build_from' => 'name',
        'save_to'    => 'slug',
    ];

	public $timestamps = false;

	static $COVER_FOLDER = '/img/covers/medias/';
	static $COVER_DEFAULT = '/assets/im/img-default.jpg';
	static $COVER_DEFAULT_DISPLAY = '/assets/im/img-display.jpg';
	static $COVER_DEFAULT_PRESS = '/assets/im/img-press.png';
	static $TECHNICAL_DOC_FOLDER = '/file/medias/';

	// Do not fill "datas" column to avoid empty string for integer
	protected $fillable = [
		'name',
		'ad_network_id',
		'broadcasting_area_id',
		'support_id',
		'theme_id',
		'category_id',
		'frequency_id',
		'cover',
		'technical_doc',
		'datas'
	];

	protected $appends = [
		'cover_img',
		'technical_doc_path',
	];

	protected static $search_rules = [
		'media.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
		'media.frequency' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
		'broadcasting_area.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
		'support.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
		'theme.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
		'ad_network.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
		'category.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
		'frequency.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
	];

	protected static $order_by = ['name' => 'ASC'];

	public function getCoverPathAttribute()
	{
		return self::$COVER_FOLDER . $this->cover;
	}

	public function getCoverImgAttribute()
	{
		if(strlen($this->cover) > 0) {
			return $this->cover_path;
		}
		if($this->support_id === 1) {
			return self::$COVER_DEFAULT_DISPLAY;
		}
		if($this->support_id === 2) {
			return self::$COVER_DEFAULT_PRESS;
		}
		return self::$COVER_DEFAULT;
	}

	public function getTechnicalDocPathAttribute()
	{
		if (strlen($this->technical_doc) > 0) {
			return self::$TECHNICAL_DOC_FOLDER . $this->technical_doc;
		}
		return null;
	}

	public function adNetwork()
	{
		return $this->belongsTo('App\AdNetwork');
	}

	public function adPlacements()
	{
		return $this->hasMany('App\AdPlacement');
	}

	public function support()
	{
		return $this->belongsTo('App\Support');
	}

	public function targets()
	{
		return $this->belongsToMany('App\Target');
	}

	public function theme()
	{
		return $this->belongsTo('App\Theme');
	}

	public function broadcastingArea()
	{
		return $this->belongsTo('App\BroadcastingArea');
	}

	public function frequency()
	{
		return $this->belongsTo('App\Frequency');
	}

	public function category()
	{
		return $this->belongsTo('App\Category');
	}

	protected static function getBaseQuery()
	{
		$query = self::query()
			->selectRaw(
				'media.*,
				media.name as media_name,
				support.name as support_name,
				broadcasting_area.name as broadcasting_area_name,
				ad_network.name as ad_network_name,
				theme.name as theme_name,
				category.name as category_name,
				frequency.name as frequency__name'
			)->leftJoin('broadcasting_area', 'media.broadcasting_area_id', '=', 'broadcasting_area.id')
			->leftJoin('frequency', 'media.frequency_id', '=', 'frequency.id')
			->leftJoin('category', 'media.category_id', '=', 'category.id')
			->leftJoin('support', 'media.support_id', '=', 'support.id')
			->leftJoin('ad_network', 'media.ad_network_id', '=', 'ad_network.id')
			->leftJoin('theme', 'media.theme_id', '=', 'theme.id');

		if (Auth::ad_network()->check()) {
			$query->where('media.ad_network_id', Auth::ad_network()->get()->ad_network_id);
		}

		return $query;
	}
}
