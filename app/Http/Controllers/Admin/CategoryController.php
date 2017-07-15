<?php namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;

use Illuminate\Http\Request;

class CategoryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$categories = Category::search($request->all(), []);

			return response()->json($categories);
		}

		return view('admin.category.index');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StoreCategoryRequest $request
	 * @return Response
	 */
	public function store(StoreCategoryRequest $request)
	{
		$category = Category::create($request->all());

		return response()->json(compact('category'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param Category $category
	 * @return Response
	 */
	public function update(UpdateCategoryRequest $request, Category $category)
	{
		$category->fill($request->all());
		$category->save();

		return response()->json(compact('category'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Category $category
	 * @return Response
	 */
	public function destroy(Category $category)
	{
		$category->delete();

		return response()->json(['id' => $category->id]);
	}
}
