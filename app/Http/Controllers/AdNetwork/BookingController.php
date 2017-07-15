<?php namespace App\Http\Controllers\AdNetwork;

use App\Booking;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$bookings = Booking::search($request->all(), [
				'adPlacement.media',
				'user.buyer',
			]);

			$bookings->each(function($booking) {
				$booking->append([
					'is_winner',
					'payment_is_valid',
					'is_lost',
					'payment_is_pending',
					'is_ready_for_transfer_validation',
					'is_ready_for_publication'
				]);

				$booking->user->buyer->append('type_fr');
			});

			return response()->json($bookings);
		}
	}

	public function postValidatePublication(Request $request, Booking $booking)
	{
		if ($booking->validatePublication()) {
			return response()->json($booking);
		}

		return response()->json(['error' => 'Echec de la publication. Veuillez réessayer.'], 422);
	}
}
