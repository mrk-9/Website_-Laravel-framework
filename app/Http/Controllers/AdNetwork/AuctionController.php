<?php namespace App\Http\Controllers\AdNetwork;

use App\Auction;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuctionController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$auctions = Auction::search($request->all(), [
				'adPlacement.media',
				'user.buyer',
			]);

			$auctions->each(function($auction) {
				$auction->append([
					'is_winner',
					'payment_is_valid',
					'payment_is_pending',
					'is_ready_for_publication',
					'is_ready_for_transfer_validation',
					'is_lost'
				]);

				$auction->user->buyer->append('type_fr');
			});

			return response()->json($auctions);
		}
	}

	public function postValidatePublication(Request $request, Auction $auction)
	{
		if ($auction->validatePublication()) {
			return response()->json($auction);
		}

		return response()->json(['error' => 'Echec de la publication. Veuillez réessayer.'], 422);
	}
}
