<?php namespace App\Http\Controllers\Admin;

use App\Buyer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateBuyerRequest;
use Illuminate\Http\Request;
use App\Events\BuyerSubscriptionWasAccepted;

class BuyerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$buyers = Buyer::search($request->all(), ['referent']);

			return response()->json($buyers);
		}

		return view('admin.buyer.index');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Buyer $buyer
	 * @return Response
	 */
	public function update(UpdateBuyerRequest $request, Buyer $buyer)
	{
		$status_before = $buyer->status;

		$buyer->fill($request->all());
		$buyer->save();

		$status_after = $buyer->status;

		if ($status_after !== $status_before && $status_after === Buyer::STATUS_VALID) {
			event(new BuyerSubscriptionWasAccepted($buyer->referent));
		}

		return response()->json(compact('buyer'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Buyer $buyer
	 * @return Response
	 */
	public function destroy(Buyer $buyer)
	{
		$buyer->delete();

		return response()->json(['id' => $buyer->id]);
	}

	/**
	* Display specific resource
	*
	* @return Response
	*/
	public function show(Buyer $buyer, Request $request) {
		return view('admin.buyer.show', compact('buyer'));
	}
}
