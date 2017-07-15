<?php namespace App\Http\Controllers\AdNetwork\Api;

use App\AdNetwork;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Request;

class AdNetworkController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$ad_networks = AdNetwork::where('name', 'ILIKE', '%' . Request::get('name') . '%')->get();

		return response()->json(compact('ad_networks'));
	}

}
