<?php namespace App\Http\Controllers\Admin;

use App\Auction;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
			});

			return response()->json($auctions);
		}
	}

	public function postValidateTransfer(Request $request, Auction $auction)
	{
		if ($auction->validateTransfer()) {
			return response()->json($auction);
		}

		return response()->json(['error' => 'Echec du virement. Veuillez réessayer.'], 422);
	}

	public function postValidatePublication(Request $request, Auction $auction)
	{
		if ($auction->validatePublication()) {
			return response()->json($auction);
		}

		return response()->json(['error' => 'Echec de la publication. Veuillez réessayer.'], 422);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Auction $auction
	 * @return Response
	 */
	public function destroy(Auction $auction)
	{
		$auction->delete();

		return response()->json(['id' => $auction->id]);
	}

	/**
	 * Display specific resource
	 *
	 * @param Auction $auction
	 * @return Response
	 */
	public function show(Auction $auction) {

		$auction->load(
			'user.buyer',
			'adPlacement.media.category',
			'adPlacement.media.support',
			'adPlacement.media.targets',
			'adPlacement.media.theme',
			'adPlacement.media.broadcastingArea',
			'adPlacement.media.adNetwork'
		);

		return view('admin.auction.show', compact('auction'));
	}

}
