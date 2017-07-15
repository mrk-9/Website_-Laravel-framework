<?php namespace App\Http\Controllers\Main\Api;

use App\Http\Controllers\Controller;
use App\Support;

class ThemeController extends Controller {

    public function getSupportThemes(Support $support)
    {
        return response()->json($support->themes);
    }

}
