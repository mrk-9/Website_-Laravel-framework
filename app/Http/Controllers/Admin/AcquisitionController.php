<?php namespace App\Http\Controllers\Admin;

use App\Acquisition;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;


class AcquisitionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$acquisitions = Acquisition::search($request->all(), ['adPlacement.media', 'invoice', 'user']);

			return response()->json($acquisitions);
		}
	}

	/**
	 * Display specific resource
	 *
	 * @return Response
	 */
	public function show(Acquisition $acquisition) {
		$acquisition->load(
			'adPlacement.media.supportType.category',
			'adPlacement.media.supportType.support',
			'adPlacement.media.target',
			'adPlacement.media.theme',
			'adPlacement.media.broadcastingArea'
		);

		return view('admin.acquisition.show', compact('acquisition'));
	}
}
