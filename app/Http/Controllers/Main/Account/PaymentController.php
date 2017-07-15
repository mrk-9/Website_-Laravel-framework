<?php namespace App\Http\Controllers\Main\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Main\SaveBuyerRequest;
use Auth;

class PaymentController extends Controller
{

    public function getBankDetails()
    {
        $credit_card = Auth::user()->get()->buyer->credit_card;
        return view('main.account.bank_details', compact('credit_card'));
    }


}
