<?php namespace App\Http\Controllers\Admin;

use App\Frequency;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\StoreMediaRequest;
use App\Http\Requests\Admin\UpdateMediaRequest;
use App\Http\Requests\AdNetwork\MediaCoverRequest;
use App\Http\Requests\AdNetwork\MediaTechnicalDocRequest;
use App\Media;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$medias = Media::search($request->all(), ['adNetwork', 'broadcastingArea', 'support', 'category', 'theme', 'frequency', 'targets']);

			return response()->json($medias);
		}

		$frequencies = Frequency::orderBy('name')->get();

		return view('admin.media.index', compact('frequencies'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StoreMediaRequest $request
	 * @return Response
	 */
	public function store(StoreMediaRequest $request, Media $media)
	{
		$media->fill($request->all());
		$media->save(); // to have media->id

		$media->targets()->attach($request->get('target_id', []));

		return response()->json(compact('media'));
	}

	public function addCover(MediaCoverRequest $request, Media $media)
	{
		if (strlen($media->cover_path) > 0 && File::exists($media->cover_path)) {
			File::delete($media->cover_path);
		}
		$file = $request->file('cover');
		$filename = Str::slug($media->name) . '-' . $media->id . '.' . $file->getClientOriginalExtension();

		$file->move(public_path(Media::$COVER_FOLDER), $filename);

		$media->cover = $filename;
		$media->save();

		return response()->json(compact('media'));
	}

	public function addTechnicalDoc(MediaTechnicalDocRequest $request, Media $media)
	{
		if (strlen($media->technical_doc_path) > 0 && File::exists($media->technical_doc_path)) {
			File::delete($media->technical_doc_path);
		}
		$file = $request->file('technical_doc');
		$filename =  Str::slug($media->name) . '-' . $media->id . '.' . $file->getClientOriginalExtension();

		$file->move(public_path(Media::$TECHNICAL_DOC_FOLDER), $filename);

		$media->technical_doc = $filename;
		$media->save();

		return response()->json(compact('media'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Media $media
	 * @return Response
	 */
	public function update(UpdateMediaRequest $request, Media $media)
	{
		$media->fill($request->all());

		if (!$request->has('category_id')) {
			$media->category_id = null;
		}

		if (!$request->has('theme_id')) {
			$media->theme_id = null;
		}

		$media->save();
		$media->targets()->sync($request->get('target_id', []));

		return response()->json(compact('media'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Media $media
	 * @return Response
	 */
	public function destroy(Media $media)
	{

		if (strlen($media->technical_doc_path) > 0 && File::exists($media->technical_doc_path)) {
			File::delete($media->technical_doc_path);
		}

		if (strlen($media->cover_path) > 0 && File::exists($media->cover_path)) {
			File::delete($media->cover_path);
		}

		$media->delete();

		return response()->json(['id' => $media->id]);
	}
}
