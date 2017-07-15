<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\StoreTemplateRequest;
use App\Http\Requests\Admin\UpdateTemplateRequest;
use App\Template;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TemplateController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$templates = Template::search($request->all(), []);

			return response()->json($templates);
		}

		return view('admin.template.index');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StoreTemplateRequest $request
	 * @return Response
	 */
	public function store(StoreTemplateRequest $request, Template $template)
	{
		$template->fill($request->all());
		$template->save(); // to have template->id

		if ($request->hasFile('cover')) {
			$file = $request->file('cover');
			$filename = Str::slug($template->name) . '-' . $template->id . '.' . $file->getClientOriginalExtension();

			$file->move(public_path(Template::$COVER_FOLDER), $filename);

			$template->cover = $filename;
			$template->save();
		}

		return response()->json(compact('template'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param UpdateTemplateRequest $request
	 * @param Template $template
	 * @return Response
	 */
	public function update(UpdateTemplateRequest $request, Template $template)
	{
		$template->fill($request->all());
		$template->save();

		if($request->hasFile('cover')) {
			if (strlen($template->cover_path) > 0 && File::exists($template->cover_path)) {
				File::delete($template->cover_path);
			}
			$file = $request->file('cover');
			$filename = Str::slug($template->name) . '-' . $template->id . '.' . $file->getClientOriginalExtension();

			$file->move(public_path(Template::$COVER_FOLDER), $filename);

			$template->cover = $filename;
			$template->save();
		}

		return response()->json(compact('template'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Template $template
	 * @return Response
	 */
	public function destroy(Template $template)
	{
		if (strlen($template->cover_path) > 0 && File::exists($template->cover_path)) {
			File::delete($template->cover_path);
		}

		$template->delete();

		return response()->json(['id' => $template->id]);
	}
}
