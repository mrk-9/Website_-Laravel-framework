<?php namespace App\Http\Controllers\Admin;

use App\AdPlacement;
use App\Events\AdPlacementWasPublished;
use App\Format;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\StoreAdPlacementRequest;
use App\Http\Requests\Admin\UpdateAdPlacementRequest;
use App\Media;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AdPlacementController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$ad_placements = AdPlacement::search($request->all(), ['media.support', 'format']);

			return response()->json($ad_placements);
		}

		return view('admin.ad-placement.index');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(StoreAdPlacementRequest $request, AdPlacement $ad_placement)
	{
		$ad_placement->fill($request->all());
		$this->storeOrUpdate($request, $ad_placement);

		if ($ad_placement->type === AdPlacement::TYPE_AUCTION) {
			$ad_placement->minimum_price = $ad_placement->price;
		}

		$ad_placement->save();

		event(new AdPlacementWasPublished($ad_placement));

		return response()->json(compact('ad_placement'));
	}

	/**
	 * Update the specified resource from storage.
	 *
	 * @param Request $request
	 * @param AdPlacement $ad_placement the specified resource
	 * @return Response
	 */
	public function update(UpdateAdPlacementRequest $request, AdPlacement $ad_placement)
	{
		$ad_placement->fill($request->all());
		$this->storeOrUpdate($request, $ad_placement);

		if (!$request->has('edition')) {
			$ad_placement->edition = null;
		}

		$ad_placement->save();

		return response()->json(compact('ad_placement'));
	}

	/**
	 * Display specific resource
	 *
	 * @return Response
	 */
	public function show(AdPlacement $ad_placement)
	{
		$ad_placement->load(
			'media.category',
			'media.support',
			'media.targets',
			'media.theme',
			'media.broadcastingArea',
			'format'
		);

		return view('admin.ad-placement.show', compact('ad_placement'));
	}

	protected function storeOrUpdate($request, AdPlacement $ad_placement)
	{
		$dates = [
			'starting_at' => 'd/m/Y H:i',
			'ending_at' => 'd/m/Y H:i',
			'technical_deadline' => 'd/m/Y H:i',
			'locking_up' => 'd/m/Y H:i',
			'broadcasting_date' => 'd/m/Y',
		];

		foreach ($dates as $date => $format) {
			if ($request->has($date)) {
				$ad_placement->$date = $this->carbonFormatToDateTime($request->get($date), $format);
			} else {
				$ad_placement->$date = null;
			}
		}

		try {
			$media = Media::findOrFail($request->get('media_id'));
		} catch (ModelNotFoundException $e) {
			return response()->json(['error' => $this->mediaNotFoundErrorMessage()], 422);
		}

		// Get existing format or create one
		if (ctype_digit($request->get('format_id'))) {
			try {
				$format = Format::findOrFail($request->get('format_id'));
				$ad_placement->format_id = $format->id;
			} catch (ModelNotFoundException $e) {
				return response()->json(['error' => $this->formatNotFoundErrorMessage()], 422);
			}
		} else {
			$format = Format::firstOrCreate(['name' => $request->get('format_id')]);
			$ad_placement->format_id = $format->id;
		}

		return $ad_placement;
	}

	/**
	 * Create a carbon date for a given string date and given format
	 * and apply toDateTimeString function.
	 *
	 * @param $date
	 * @param $format
	 * @return datetime string
	 */
	protected function carbonFormatToDateTime($date, $format) {
		$formatted_date = Carbon::createFromFormat($format, $date);

		return $formatted_date->toDateTimeString();
	}

	/**
	 * Get error message if a format is not found
	 *
	 * @return string message
	 */
	protected function formatNotFoundErrorMessage()
	{
		return "Le format indiqué n'existe pas.";
	}

	/**
	 * Get error message if a media is not found
	 *
	 * @return string message
	 */
	protected function mediaNotFoundErrorMessage()
	{
		return "Le média indiqué n'existe pas.";
	}
}
