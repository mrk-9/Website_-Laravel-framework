<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Selection;
use Illuminate\Http\Request;

class SelectionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$selections = Selection::search($request->all(), [
				'adPlacement.media',
				'user',
			]);

			return response()->json($selections);
		}
	}

	/**
	 * Display specific resource
	 *
	 * @param Selection $selection
	 * @return Response
	 */
	public function show(Selection $selection) {
		$selection->load(
			'user.buyer',
			'adPlacement.media.support_type.category',
			'adPlacement.media.support_type.support',
			'adPlacement.media.target',
			'adPlacement.media.theme',
			'adPlacement.media.broadcasting_area',
			'adPlacement.media.adNetwork'
		);

		return view('admin.selection.show', compact('selection'));
	}
}
