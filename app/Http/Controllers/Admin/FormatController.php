<?php namespace App\Http\Controllers\Admin;

use App\Format;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\StoreFormatRequest;
use App\Http\Requests\Admin\UpdateFormatRequest;

use Illuminate\Http\Request;

class FormatController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$formats = Format::search($request->all(), []);

			return response()->json($formats);
		}

		return view('admin.format.index');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StoreFormatRequest $request
	 * @return Response
	 */
	public function store(StoreFormatRequest $request)
	{
		$format = Format::create($request->all());

		return response()->json(compact('format'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Format $format
	 * @return Response
	 */
	public function update(UpdateFormatRequest $request, Format $format)
	{
		$format->fill($request->all());
		$format->save();

		return response()->json(compact('format'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Format $format
	 * @return Response
	 */
	public function destroy(Format $format)
	{
		$format->delete();

		return response()->json(['id' => $format->id]);
	}
}
