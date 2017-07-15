<?php namespace App;

use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdNetworkUser extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{

	use Authenticatable, CanResetPassword, SoftDeletes;

	protected $table = 'ad_network_user';

	protected $fillable = [
		'name',
		'family_name',
		'title',
		'email',
		'phone',
		'password',
		'position',
		'ad_network_id'
	];

	protected $hidden = ['password', 'remember_token'];

	protected static $search_rules = [
		'name' => [
			'raw' => 'CONCAT("ad_network_user"."name", \' \', "ad_network_user"."family_name", \' \', "ad_network_user"."name") ILIKE \'%{value}%\''
		],
		'email' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%'
		],
		'position' => [
			'operator' => 'ILIKE',
			'value' => '%{value}%'
		]
	];

	public function adNetwork()
	{
		return $this->belongsTo('App\AdNetwork', 'ad_network_id');
	}

	/**
	 * Hash and set the password attribute
	 * @param string $password the ad network user's password
	 */
	public function setPasswordAttribute($password)
	{
		if (!empty($password)) {
			$this->attributes['password'] = Hash::make($password);
		}
	}
}
