<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Selection extends BaseModel
{

    protected $table = 'selection';

    protected $appends = ['created_at_date'];

    protected $fillable = [
        'user_id',
        'ad_placement_id',
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
    ];

    public function adPlacement()
    {
        return $this->belongsTo('App\AdPlacement');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getCreatedAtDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y');
    }

    protected static function getBaseQuery()
    {
        return self::query()
            ->selectRaw(
                'selection.*,
                ad_placement.name as ad_placement_name,
                ad_placement.edition,
                ad_placement.created_at as ad_placement_created_at'
            )->leftJoin('ad_placement', 'selection.ad_placement_id', '=', 'ad_placement.id')
            ->leftJoin('media', 'ad_placement.media_id', '=', 'media.id')
            ->orderBy('selection.created_at', 'DESC');
    }
}
