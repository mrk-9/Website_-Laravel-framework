<?php namespace App\Http\Controllers\AdNetwork\Api;

use App\SupportType;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Request;

class SupportTypeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$support_types = SupportType::where('name', 'ILIKE', '%' . Request::get('name') . '%')->get();

		$support_types->load('support', 'category');

		return response()->json(compact('support_types'));
	}

}
