<?php namespace App\Http\Controllers\Admin;

use App\AdNetwork;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdNetworkRequest;
use App\Http\Requests\Admin\StoreAdNetworkRequest;
use Illuminate\Http\Request;

class AdNetworkController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$ad_networks = AdNetwork::search($request->all(), ['referent']);

			return response()->json($ad_networks);
		}

		return view('admin.ad_network.index');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(StoreAdNetworkRequest $request, AdNetwork $ad_network)
	{
		$ad_network->fill($request->all());
		$ad_network->save();

		return response()->json(compact('ad_network'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param AdNetwork $ad_network
	 * @return Response
	 */
	public function update(UpdateAdNetworkRequest $request, AdNetwork $ad_network)
	{
		$ad_network->fill($request->all());
		$ad_network->save();

		return response()->json(compact('ad_network'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param AdNetwork $ad_network
	 * @return Response
	 */
	public function destroy(AdNetwork $ad_network)
	{
		$ad_network->delete();

		return response()->json(['id' => $ad_network->id]);
	}

	/**
	 * Display specific resource
	 *
	 * @return Response
	 */
	public function show(AdNetwork $ad_network) {
		$ad_network->append('status_fr');

		return view('admin.ad_network.show', compact('ad_network'));
	}
}
