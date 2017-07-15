<?php namespace App\Http\Controllers\Admin\Api;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Request;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::where(function($query) {
			$query->where('name', 'ILIKE', Request::get('name') . '%')
				->orWhere('family_name', 'ILIKE', Request::get('name') . '%')
				->orWhere('email', 'ILIKE', Request::get('name') . '%');
		});

		if (Request::has('buyer_id')) {
			$users = $users->where('buyer_id', Request::get('buyer_id'));
		}

		$users = $users->get();

		return response()->json(compact('users'));
	}

}
