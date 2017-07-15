<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\StoreTargetRequest;
use App\Http\Requests\Admin\UpdateTargetRequest;
use App\Target;
use Illuminate\Http\Request;

class TargetController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$targets = Target::search($request->all(), []);

			return response()->json($targets);
		}

		return view('admin.target.index');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StoreTargetRequest $request
	 * @return Response
	 */
	public function store(StoreTargetRequest $request)
	{
		$target = Target::create($request->all());

		return response()->json(compact('target'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Target $target
	 * @return Response
	 */
	public function update(UpdateTargetRequest $request, Target $target)
	{
		$target->fill($request->all());
		$target->save();

		return response()->json(compact('target'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Target $target
	 * @return Response
	 */
	public function destroy(Target $target)
	{
		$target->delete();

		return response()->json(['id' => $target->id]);
	}

}
