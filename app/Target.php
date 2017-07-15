<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;

class Target extends BaseModel implements SluggableInterface
{

	use SluggableTrait;

	protected $table = 'target';

	public $timestamps = false;

	protected $sluggable = [
		'build_from' => 'name',
		'save_to' => 'slug',
	];

	protected $fillable = ['name'];

		protected static $search_rules = [
		'target.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
	];

	protected static $order_by = ['name' => 'ASC'];
}
