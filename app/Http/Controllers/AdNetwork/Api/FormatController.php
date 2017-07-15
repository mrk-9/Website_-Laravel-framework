<?php namespace App\Http\Controllers\AdNetwork\Api;

use App\Format;
use App\Media;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class FormatController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

		$formats = Format::all();

		if ($request->has('name')) {
			$formats = Format::where('name', 'ILIKE', '%' . $request->get('name') . '%')->get();
		} else if ($request->has('media_id')) {
			$media = Media::find($request->get('media_id'));
			$media->load('support');
			$formats = Format::where('support_id', $media->support->id)->get();
		} else if ($request->has('support_id')) {
			$formats = Format::where('support_id', $request->get('support_id'))->get();
		}

		return response()->json(compact('formats'));
	}
}
