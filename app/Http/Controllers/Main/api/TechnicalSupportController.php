<?php namespace App\Http\Controllers\Main\Api;

use App\Http\Controllers\Controller;
use App\TechnicalSupport;

class TechnicalSupportController extends Controller {

    public function all()
    {
        return response()->json(TechnicalSupport::all());
    }

}
