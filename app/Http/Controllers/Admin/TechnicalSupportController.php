<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\StoreTechnicalSupportRequest;
use App\Http\Requests\Admin\UpdateTechnicalSupportRequest;
use App\TechnicalSupport;
use Illuminate\Http\Request;

class TechnicalSupportController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$technical_supports = TechnicalSupport::search($request->all(), []);

			return response()->json($technical_supports);
		}

		return view('admin.technical_support.index');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StoreTechnicalSupportRequest $request
	 * @return Response
	 */
	public function store(StoreTechnicalSupportRequest $request, TechnicalSupport $technical_support)
	{
		$technical_support->fill($request->all());
		$technical_support->save();

		return response()->json(compact('technical_support'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Support $support
	 * @return Response
	 */
	public function update(UpdateTechnicalSupportRequest $request, TechnicalSupport $technical_support)
	{
		$technical_support->fill($request->all());
		$technical_support->save();

		return response()->json(compact('technical_support'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param TechnicalSupport $technical_support
	 * @return Response
	 */
	public function destroy(TechnicalSupport $technical_support)
	{
		$technical_support->delete();

		return response()->json(['id' => $technical_support->id]);
	}
}
