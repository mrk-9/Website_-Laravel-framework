<?php namespace App\Http\Controllers\AdNetwork\Api;

use App\Theme;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class ThemeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$themes = Theme::all();

		if ($request->has('name')) {
			$themes = Theme::where('name', 'ILIKE', '%' . $request->get('name') . '%')->get();
		} else if ($request->has('support_id')) {
			$themes = Theme::where('support_id', $request->get('support_id'))->get();
		}

		return response()->json(compact('themes'));
	}

}
