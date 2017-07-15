<?php namespace App\Http\Requests\Main;

use App\Http\Requests\Request;

class ShareAdPlacementRequest extends Request {

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'message' => [],
        ];
    }

    public function messages()
    {
        return [];
    }

}
