<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\UpdateOfferRequest;
use App\Offer;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Events\OfferWasAccepted;

class OfferController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$offers = Offer::search($request->all(), [
				'adPlacement.media',
				'user.buyer',
			]);

			$offers->each(function($offer) {
				$offer->append([
					'is_winner',
					'payment_is_valid',
					'is_lost',
					'payment_is_pending',
					'is_accepted',
					'is_ready_for_transfer_validation',
					'is_ready_for_publication',
					'is_ready_to_be_accepted'
				]);
			});

			return response()->json($offers);
		}
	}

	public function postAccept(Request $request, Offer $offer)
	{
		if ($offer->accept()) {
			event(new OfferWasAccepted($offer));
			return response()->json($offer);
		}

		return response()->json(['error' => 'Echec lors de l\'acceptation de l\'offre. Veuillez réessayer.'], 422);
	}


	public function postValidateTransfer(Request $request, Offer $offer)
	{
		if ($offer->validateTransfer()) {
			return response()->json($offer);
		}

		return response()->json(['error' => 'Echec du virement. Veuillez réessayer.'], 422);
	}

	public function postValidatePublication(Request $request, Offer $offer)
	{
		if ($offer->validatePublication()) {
			return response()->json($offer);
		}

		return response()->json(['error' => 'Echec de la publication. Veuillez réessayer.'], 422);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Auction $offer
	 * @return Response
	 */
	public function destroy(Offer $offer)
	{
		$offer->delete();

		return response()->json(['id' => $offer->id]);
	}

	/**
	 * Display specific resource
	 *
	 * @param Offer $offer
	 * @return Response
	 */
	public function show(Offer $offer) {

		$offer->load(
			'user.buyer',
			'adPlacement.media.category',
			'adPlacement.media.support',
			'adPlacement.media.targets',
			'adPlacement.media.theme',
			'adPlacement.media.broadcastingArea',
			'adPlacement.media.adNetwork'
		);

		return view('admin.offer.show', compact('offer'));
	}
}
