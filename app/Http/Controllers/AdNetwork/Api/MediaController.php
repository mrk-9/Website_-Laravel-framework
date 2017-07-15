<?php namespace App\Http\Controllers\AdNetwork\Api;

use App\Media;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Request;

class MediaController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$medias = Media::getBaseQuery()->where('media.name', 'ILIKE', '%' . Request::get('name') . '%')->get();
		$medias->load('support');

		return response()->json(compact('medias'));
	}

}
