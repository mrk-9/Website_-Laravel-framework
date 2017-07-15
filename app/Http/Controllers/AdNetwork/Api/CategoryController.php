<?php namespace App\Http\Controllers\AdNetwork\Api;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class CategoryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$categories = Category::all();

		if ($request->has('name')) {
			$categories = Category::where('name', 'ILIKE', '%' . $request->get('name') . '%')->get();
		} else if ($request->has('support_id')) {
			$categories = Category::where('support_id', $request->get('support_id'))->get();
		}

		return response()->json(compact('categories'));
	}

}
