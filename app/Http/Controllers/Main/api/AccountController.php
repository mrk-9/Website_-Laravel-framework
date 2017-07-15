<?php namespace App\Http\Controllers\Main\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Main\SaveBuyerRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AccountController extends Controller {

    public function postCreditCard(Request $request)
    {
            $user = Auth::user()->get();
            $stripe_token = $request->get('stripe_token');
            if (is_null($stripe_token)) {
                return response()->json(["stripe" => 'Un \'stripe_token\' est requis pour ajouter une carte de crédit à cet utilisateur'], 422);
            } else {

                    $user->buyer->saveCreditCard($stripe_token);

                return response()->json('Carte sauvegardée avec succès');
            }
    }

    public function deleteCreditCard()
    {
        $user = Auth::user()->get();
        if (is_null($user->buyer->credit_card)) {
            return response()->json(["stripe" => 'Aucune carte n\'exite pour cet utilisateur'], 422);
        } else {
            $user->buyer->saveCreditCard(null);
            return response()->json('Carte supprimée avec succès');
        }
    }

    public function getCreditCard()
    {
        $card = Auth::user()->get()->buyer->credit_card;
        return response()->json(compact('card'));
    }

}
