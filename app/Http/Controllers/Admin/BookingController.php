<?php namespace App\Http\Controllers\Admin;

use App\Booking;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
			});

			return response()->json($bookings);
		}
	}

	public function postValidateTransfer(Request $request, Booking $booking)
	{
		if ($booking->validateTransfer()) {
			return response()->json($booking);
		}

		return response()->json(['error' => 'Echec du virement. Veuillez réessayer.'], 422);
	}

	public function postValidatePublication(Request $request, Booking $booking)
	{
		if ($booking->validatePublication()) {
			return response()->json($booking);
		}

		return response()->json(['error' => 'Echec de la publication. Veuillez réessayer.'], 422);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Booking $booking
	 * @return Response
	 */
	public function destroy(Booking $booking)
	{
		$booking->delete();

		return response()->json(['id' => $booking->id]);
	}

	/**
	 * Display specific resource
	 *
	 * @param Booking $booking
	 * @return Response
	 */
	public function show(Booking $booking) {

		$booking->load(
			'user.buyer',
			'adPlacement.media.category',
			'adPlacement.media.support',
			'adPlacement.media.targets',
			'adPlacement.media.theme',
			'adPlacement.media.broadcastingArea',
			'adPlacement.media.adNetwork'
		);

		return view('admin.booking.show', compact('booking'));
	}
}
