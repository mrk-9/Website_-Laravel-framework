<?php namespace App\Http\Requests\Main;

use App\Http\Requests\Request;

class ContactRequest extends Request {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'lastname' => ['required', 'max:255'],
            'firstname' => ['required', 'max:255'],
            'phone' => ['max:255'],
            'email' => ['required', 'email', 'max:255'],
            'account_type' => ['max:255'],
            'note' => ['required'],
        ];
    }

}
