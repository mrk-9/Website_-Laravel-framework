<?php namespace App;

use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;

class Support extends BaseModel implements SluggableInterface
{

	use SluggableTrait;

	protected $table = 'support';

	public $timestamps = false;

	protected $sluggable = [
		'build_from' => 'name',
		'save_to' => 'slug',
	];

	protected $fillable = ['name'];

	protected static $search_rules = [
		'support.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
	];

	protected static $order_by = ['name' => 'ASC'];

	public function themes()
	{
		return $this->hasMany('App\Theme')->orderBy('name');
	}

	public function formats()
	{
		return $this->hasMany('App\Format')->orderBy('name');
	}

	public function categories()
	{
		return $this->hasMany('App\Category')->orderBy('name');
	}
}
