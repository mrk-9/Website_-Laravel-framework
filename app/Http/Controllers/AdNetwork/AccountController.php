<?php namespace App\Http\Controllers\AdNetwork;

use App\AdNetworkUser;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\AdNetwork\UpdateAccountRequest;
use App\Media;
use Auth;
use Illuminate\Http\Request;

class AccountController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function edit(Request $request)
	{
		$ad_network = Auth::ad_network()->get()->adNetwork;
		$referent = AdNetworkUser::where('id', $ad_network->ad_network_user_id)->first();
		$media_count = Media::getBaseQuery()->count();

		return view('ad_network.account.edit', compact('ad_network', 'referent', 'media_count'));
	}

	public function update(UpdateAccountRequest $request)
	{
		// return response()->json($request->all(), 500);
		$ad_network = Auth::ad_network()->get()->adNetwork;
		$referent = AdNetworkUser::where('id', $ad_network->ad_network_user_id)->first();

		$ad_network->fill($request->all());
		$referent->fill($request->all());

		$referent->name = $request->get('referent_name');
		$referent->phone = $request->get('referent_phone');
		$referent->email = $request->get('referent_email');

		if ($ad_network->save() && $referent->save()) {
			return response()->json(compact('ad_network', 'referent'));
		}

		return response()->json(["Une erreur est survenue. Veuillez réessayer."], 422);
	}
}
