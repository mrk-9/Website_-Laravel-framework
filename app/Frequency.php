<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;

class Frequency extends BaseModel implements SluggableInterface
{
	use SluggableTrait;

	protected $table = 'frequency';

	public $timestamps = false;

	protected $sluggable = [
		'build_from' => 'name',
		'save_to' => 'slug',
	];

	protected $fillable = ['name'];
}
