<?php namespace App\Http\Controllers\Admin;

use App\AdNetwork;
use App\AdNetworkUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdNetworkUserRequest;
use App\Http\Requests\Admin\StoreAdNetworkUserRequest;
use Illuminate\Http\Request;

class AdNetworkUserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$ad_network_users = AdNetworkUser::search($request->all(), ['adNetwork']);

			return response()->json($ad_network_users);
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(StoreAdNetworkUserRequest $request, AdNetworkUser $ad_network_user)
	{
		$ad_network_user->fill($request->all());
		$ad_network_user->save();

		if ($request->has('referent')) {
			$ad_network = $ad_network_user->adNetwork;
			$ad_network->ad_network_user_id = $ad_network_user->id;
			$ad_network->save();
		}

		return response()->json(compact('ad_network_user'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param AdNetworkUser $ad_network_user
	 * @return Response
	 */
	public function update(UpdateAdNetworkUserRequest $request, AdNetworkUser $ad_network_user)
	{
		$ad_network_user->fill($request->all());

		if ($request->has('referent')) {
			$ad_network = $ad_network_user->adNetwork;
			$ad_network->ad_network_user_id = $ad_network_user->id;
			$ad_network->save();
		}

		$ad_network_user->save();

		return response()->json(compact('ad_network_user'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param AdNetworkUser $ad_network_user
	 * @return Response
	 */
	public function destroy(AdNetworkUser $ad_network_user)
	{
		$ad_network_user->delete();

		return response()->json(['id' => $ad_network_user->id]);
	}
}
