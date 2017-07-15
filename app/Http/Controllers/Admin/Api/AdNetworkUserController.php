<?php namespace App\Http\Controllers\Admin\Api;

use App\AdNetworkUser;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Request;

class AdNetworkUserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$ad_network_users = AdNetworkUser::where(function($query) {
			$query->where('name', 'ILIKE', Request::get('name') . '%')
				->orWhere('family_name', 'ILIKE', Request::get('name') . '%')
				->orWhere('email', 'ILIKE', Request::get('name') . '%');
		});

		if (Request::has('ad_network')) {
			$ad_network_users = $ad_network_users->where('ad_network_id', Request::get('ad_network'));
		}

		$ad_network_users = $ad_network_users->get();

		return response()->json(compact('ad_network_users'));
	}
}
