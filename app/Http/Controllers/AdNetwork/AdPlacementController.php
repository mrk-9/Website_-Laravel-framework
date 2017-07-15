<?php namespace App\Http\Controllers\AdNetwork;

use App\Acquisition;
use App\AdPlacement;
use App\Auction;
use App\Booking;
use App\Events\AdPlacementWasPublished;
use App\Format;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\AdNetwork\DestroyAdPlacementRequest;
use App\Http\Requests\AdNetwork\StoreAdPlacementRequest;
use App\Http\Requests\AdNetwork\UpdateAdPlacementRequest;
use App\Media;
use App\Offer;
use App\Selection;
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
			$ad_placements = AdPlacement::search($request->all(), ['media.support', 'format', 'acquisitions']);

			$ad_placements->each(function($ad_placement) {
				$ad_placement->append('bought');
				$ad_placement->append('winner');
			});

			return response()->json($ad_placements);
		}

		return view('ad_network.ad_placement.index');
	}

	public function create(Request $request)
	{
		if ($request->has('media_id')) {
			try {
				$media = Media::findOrFail($request->get('media_id'));
			} catch (ModelNotFoundException $e) {
				abort(404);
			}

			return view('ad_network.media.ad_placement.create', compact('media'));
		}

		return view('ad_network.ad_placement.create');
	}

	public function update(UpdateAdPlacementRequest $request, AdPlacement $ad_placement)
	{
		$has_new_acquisitions = false;

		if ($request->has('type') && $ad_placement->acquisitions->count() > 0) {
			$ad_placement->fill($request->except(['minimum_price', 'price', 'type']));
			$has_new_acquisitions = true;
		} else {
			$ad_placement->fill($request->all());

			if ($ad_placement->type === AdPlacement::TYPE_AUCTION) {
				$ad_placement->minimum_price = $ad_placement->price;
			}
		}

		$this->storeOrUpdate($request, $ad_placement);

		if (!$request->has('edition')) {
			$ad_placement->edition = null;
		}

		$ad_placement->save();

		if ($has_new_acquisitions) {
			return response()->json(['modal' => '#NewAcquisitionInfo']);
		}

		return response()->json(compact('ad_placement'));
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
	 * Remove the specified resource from storage.
	 *
	 * @param AdNetworkUser $ad_network_user
	 * @return Response
	 */
	public function destroy(DestroyAdPlacementRequest $request, AdPlacement $ad_placement)
	{
		if (!is_null($ad_placement->winner)) {
			return response()->json(["error" => $this->winnerAlreadyExistsErrorMessage()], 422);
		}

		$selections = $ad_placement->selections->pluck('id')->all();
        $bookings = $ad_placement->bookings->pluck('id')->all();
        $offers = $ad_placement->offers->pluck('id')->all();
        $auctions = $ad_placement->auctions->pluck('id')->all();

	    Selection::destroy($selections);
        Booking::destroy($bookings);
        Offer::destroy($offers);
        Auction::destroy($auctions);

		$ad_placement->deletion_cause = $request->get('deletion_cause');
		$ad_placement->save();

		$ad_placement->delete();

		return response()->json(['id' => $ad_placement->id]);
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

		$acquisition = Acquisition::where('ad_placement_id', $ad_placement->id)
			->where('transfer_status', Acquisition::TRANSFER_SUCCESS)
			->orWhere('charge_status', Acquisition::CHARGE_SUCCESS)
			->first();

		return view('ad_network.ad_placement.show', compact('ad_placement', 'acquisition'));
	}

	/**
	 * Validate publication for the specified winner
	 *
	 * @param $winner which represents an auction, a booking or an offer
	 */
	public function postValidatePublication(AdPlacement $ad_placement)
	{
		$ad_placement->winner->validatePublication();
	}

	/**
	 * Store and update logic for an ad placement
	 *
	 * @param Request $request
	 * @param  AdPlacement $ad_placement
	 * @return Response
	 */
	protected function storeOrUpdate($request, AdPlacement $ad_placement)
	{
		// dates with their current format
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
	 * Get error message if a winner already exists
	 *
	 * @return string message
	 */
	protected function winnerAlreadyExistsErrorMessage()
	{
		return "Une offre ou une réservation est déjà gagnante. Suppression impossible.";
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
