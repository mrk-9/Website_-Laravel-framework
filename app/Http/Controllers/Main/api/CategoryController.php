<?php namespace App\Http\Controllers\Main\Api;

use App\Http\Controllers\Controller;
use App\Support;

class CategoryController extends Controller {

    public function getSupportCategories(Support $support)
    {
        return response()->json($support->categories);
    }

}
