<?php namespace App\Http\Requests\Main;

use App\Http\Requests\Request;
use App\Http\Requests\Main\CreateBuyerRequest;
use Auth;

class UpdateBuyerRequest extends CreateBuyerRequest {



    public function rules()
    {
        $this->rules['password'] = ['confirmed', 'min:6', 'max:255'];
        $this->rules['email'] = ['required', 'email', 'unique:user,email,' . Auth::user()->get()->id, 'max:255'];
        $this->rules['buyer_email'] = ['required', 'email', 'unique:buyer,email,' . Auth::user()->get()->buyer->id, 'max:255'];
        unset($this->rules['password_confirmation']);
        return $this->rules;
    }

}
