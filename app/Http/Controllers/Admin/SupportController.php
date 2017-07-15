<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\UpdateSupportRequest;
use App\Http\Requests\Admin\StoreSupportRequest;
use App\Support;
use Illuminate\Http\Request;

class SupportController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$supports = Support::search($request->all(), []);

			return response()->json($supports);
		}

		return view('admin.support.index');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StoreSupportRequest $request
	 * @return Response
	 */
	public function store(StoreSupportRequest $request)
	{
		$support = Support::create($request->all());

		return response()->json(compact('support'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Support $support
	 * @return Response
	 */
	public function update(UpdateSupportRequest $request, Support $support)
	{
		$support->fill($request->all());
		$support->save();

		return response()->json(compact('support'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Support $support
	 * @return Response
	 */
	public function destroy(Support $support)
	{
		$support->delete();

		return response()->json(['id' => $support->id]);
	}
}
