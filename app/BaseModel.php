<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

	protected static $search_rules = [];

	public static function search(array $params, array $relationships, $query = null)
	{
		if (is_null($query)) {
			$query = static::getBaseQuery();
		}

		$query->with($relationships);

		foreach (array_get($params, 'search', []) as $key => $value) {
			if (array_key_exists($key, static::$search_rules)) {
				$search_rule = static::$search_rules[$key];

				if (array_key_exists('raw', $search_rule)) {
					$query->whereRaw(str_replace('{value}', $value, $search_rule['raw']));
				} else {
					$query->where($key, $search_rule['operator'], str_replace('{value}', $value, $search_rule['value']));
				}
			} elseif ($value == 'null') {
				$query->whereNull($key);
			} elseif ($value == 'not null') {
				$query->whereNotNull($key);
			} else {
				$query->where($key, $value);
			}
		}

		$sort = json_decode($params['sort'], true);

		if ($sort && isset($sort['predicate']) && isset($sort['reverse'])) {
			$query->orderBy($sort['predicate'], $sort['reverse'] ? 'DESC' : 'ASC');
		} else {
			if ((new static)->timestamps) {
				$query->orderBy(with(new static)->getTable() . '.created_at', 'DESC');
			} else {
				foreach (static::$order_by as $column => $order) {
					$query->orderBy($column, $order);
				}
			}
		}

		return $query->paginate(array_get($params, 'per_page', 20));
	}

	protected static function getBaseQuery()
	{
		return self::query();
	}
}
