<?php namespace App\Http\Controllers\Main\Api;

use App\Http\Controllers\Controller;
use App\Support;

class FormatController extends Controller {

    public function getSupportFormats(Support $support)
    {
        return response()->json($support->formats);
    }

}
