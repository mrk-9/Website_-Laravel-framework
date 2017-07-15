<?php namespace App;

use Hash;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract
{

	use Authenticatable, CanResetPassword, SoftDeletes;

	protected $table = 'user';

	protected $fillable = ['name', 'family_name', 'title', 'email', 'phone', 'function', 'password', 'buyer_id'];

	protected $hidden = ['password', 'remember_token'];

	protected static $search_rules = [
		'name' => [
			'raw' => 'CONCAT("user"."name", \' \', "user"."family_name", \' \', "user"."name") LIKE \'%{value}%\''
		],
		'email' => [
			'operator' => 'LIKE',
			'value' => '%{value}%'
		],
		'function' => [
			'operator' => 'LIKE',
			'value' => '%{value}%'
		]
	];

	public function buyer()
	{
		return $this->belongsTo('App\Buyer');
	}

	public function bookings()
	{
		return $this->hasMany('App\Booking');
	}

	public function selections()
	{
		return $this->hasMany('App\Selection');
	}

	public function acquisitions()
	{
		return $this->hasMany('App\Acquisition');
	}

	/**
	 * Hash and set the password attribute
	 * @param string $password the user's password
	 */
	public function setPasswordAttribute($password)
	{
		if (!empty($password)) {
			$this->attributes['password'] = Hash::make($password);
		}
	}
}
