<?php namespace App\Http\Controllers\AdNetwork;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Offer;
use Illuminate\Http\Request;
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

				$offer->user->buyer->append('type_fr');
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

	public function postValidatePublication(Request $request, Offer $offer)
	{
		if ($offer->validatePublication()) {
			return response()->json($offer);
		}

		return response()->json(['error' => 'Echec de la publication. Veuillez réessayer.'], 422);
	}
}
