<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;

class Template extends BaseModel implements SluggableInterface
{

	static $COVER_FOLDER = '/img/covers/templates/';

    use SluggableTrait;

    protected $table = 'template';

    public $timestamps = false;

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug',
    ];

    protected $fillable = ['name', 'description', 'cover'];

	protected $appends = [
		'cover_path',
	];

	protected static $search_rules = [
		'template.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
		'template.description' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
	];

	protected static $order_by = ['name' => 'ASC'];

	public function getCoverPathAttribute()
	{
		if (strlen($this->cover) > 0) {
			return Template::$COVER_FOLDER . $this->cover;
		}
		return null;
	}

}
