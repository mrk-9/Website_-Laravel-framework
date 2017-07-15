<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;

class Format extends BaseModel implements SluggableInterface
{

    use SluggableTrait;

    protected $table = 'format';

    public $timestamps = false;

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug',
    ];

    protected $fillable = ['name', 'support_id'];

    protected static $search_rules = [
        'format.name' => [
            'operator' => 'ILIKE',
            'value' => '%{value}%',
        ],
    ];

    protected static $order_by = ['name' => 'ASC'];

}
