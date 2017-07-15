<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;

class TechnicalSupport extends BaseModel implements SluggableInterface
{

    use SluggableTrait;

    protected $table = 'technical_support';

    public $timestamps = false;

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug',
    ];

    protected $fillable = ['name', 'description', 'price'];

	protected $casts = [
		'price' => 'float',
	];

    protected static $search_rules = [
		'technical_support.name' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
		'technical_support.description' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%',
		],
	];

	protected static $order_by = ['name' => 'ASC'];
}
