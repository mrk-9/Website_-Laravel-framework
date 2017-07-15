<?php namespace App\Http\Controllers\Admin;

use App\BroadcastingArea;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\StoreBroadcastingAreaRequest;
use App\Http\Requests\Admin\UpdateBroadcastingAreaRequest;
use Illuminate\Http\Request;

class BroadcastingAreaController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$broadcasting_areas = BroadcastingArea::search($request->all(), []);

			return response()->json($broadcasting_areas);
		}

		return view('admin.broadcasting_area.index');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StoreBroadcastingAreaRequest $request
	 * @return Response
	 */
	public function store(StoreBroadcastingAreaRequest $request)
	{
		$broadcasting_area = BroadcastingArea::create($request->all());

		return response()->json(compact('broadcasting_area'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param BroadcastingArea $broadcasting_area
	 * @return Response
	 */
	public function update(UpdateBroadcastingAreaRequest $request, BroadcastingArea $broadcasting_area)
	{
		$broadcasting_area->fill($request->all());
		$broadcasting_area->save();

		return response()->json(compact('broadcasting_area'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param BroadcastingArea $broadcasting_area
	 * @return Response
	 */
	public function destroy(BroadcastingArea $broadcasting_area)
	{
		$broadcasting_area->delete();

		return response()->json(['id' => $broadcasting_area->id]);
	}
}
