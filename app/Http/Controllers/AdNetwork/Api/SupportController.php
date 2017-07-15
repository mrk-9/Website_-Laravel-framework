<?php namespace App\Http\Controllers\AdNetwork\Api;

use App\Support;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Request;

class SupportController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$supports = Support::where('name', 'ILIKE', '%' . Request::get('name') . '%')->get();

		return response()->json(compact('supports'));
	}

}
