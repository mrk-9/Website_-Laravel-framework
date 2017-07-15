<?php namespace App\Http\Controllers\Main\Api;

use App\Events\ContactSend;
use App\Http\Controllers\Controller;
use App\Http\Requests\Main\ContactRequest;

class ContactController extends Controller {

    public function sendContact(ContactRequest $request)
    {
        event(new ContactSend($request->get('lastname'), $request->get('firstname'), $request->get('email'), $request->get('phone'), $request->get('account_type'), $request->get('note')));
        return response()->json("ok");
    }

}
