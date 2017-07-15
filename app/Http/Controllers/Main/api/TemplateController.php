<?php namespace App\Http\Controllers\Main\Api;

use App\Http\Controllers\Controller;
use App\Template;

class TemplateController extends Controller {

    public function all()
    {
        return response()->json(Template::all());
    }

}
