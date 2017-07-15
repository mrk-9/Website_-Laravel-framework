<?php namespace App\Http\Controllers\Main\Account;

use App\Buyer;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Main\UpdateBuyerRequest;
use Auth;

class HomeController extends Controller {

	public function getIndex()
	{
		return view('main.account.index');
	}

	public function postForm(UpdateBuyerRequest $request)
	{
		$user = Auth::user()->get();
		$buyer = $user->buyer;

		$user->fill($request->all());

		$buyer->fill([
			'type' => $request->get('buyer_type'),
			'name' => $request->get('buyer_name'),
			'company_type' => $request->get('buyer_company_type'),
			'activity' => $request->get('buyer_activity'),
			'address' => $request->get('buyer_address'),
			'zipcode' => $request->get('buyer_zipcode'),
			'city' => $request->get('buyer_city'),
			'phone' => $request->get('buyer_phone'),
			'email' => $request->get('buyer_email'),
			'customers' => $request->get('buyer_customers')
		]);

		if($buyer->type ==  Buyer::TYPE_AGENCY) {
			$buyer->activity = null;
		} else {
			$buyer->customers = null;
		}

		$buyer->save();
		$user->save();

		return response()->json(compact('user', 'buyer', 'success'));
	}

}
