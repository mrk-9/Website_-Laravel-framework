<?php namespace App\Http\Controllers\Admin\Api;

use App\Media;
use App\Target;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class TargetController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$targets = Target::all();

		if ($request->has('name')) {
			$targets = Target::where('name', 'ILIKE', '%' . $request->get('name') . '%')->get();
		} else if ($request->has('media_id')) {
			$media = Media::find($request->get('media_id'));
			$targets = $media->targets->pluck('id');
		}

		return response()->json(compact('targets'));
	}

}
