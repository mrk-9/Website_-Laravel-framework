<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\StoreThemeRequest;
use App\Http\Requests\Admin\UpdateThemeRequest;
use App\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$themes = Theme::search($request->all(), []);

			return response()->json($themes);
		}

		return view('admin.theme.index');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StoreThemeRequest $request
	 * @return Response
	 */
	public function store(StoreThemeRequest $request)
	{
		$theme = Theme::create($request->all());

		return response()->json(compact('theme'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Theme $theme
	 * @return Response
	 */
	public function update(UpdateThemeRequest $request, Theme $theme)
	{
		$theme->fill($request->all());
		$theme->save();

		return response()->json(compact('theme'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Theme $theme
	 * @return Response
	 */
	public function destroy(Theme $theme)
	{
		$theme->delete();

		return response()->json(['id' => $theme->id]);
	}
}
