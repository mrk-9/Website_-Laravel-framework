<?php namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Requests\Admin\UpdateAdminRequest;
use Illuminate\Http\Request;

class AdminController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$admins = Admin::search($request->all(), []);

			return response()->json($admins);
		}

		return view('admin.admin.index');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(StoreAdminRequest $request, Admin $admin)
	{
		$admin->fill($request->all());
		$admin->save();

		return response()->json(compact('admin'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(UpdateAdminRequest $request, Admin $admin)
	{
		$admin->fill($request->all());
		$admin->save();

		return response()->json(compact('admin'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Admin $admin)
	{
		$admin->delete();

		return response()->json(['id' => $admin->id]);
	}

	protected function getAdminList(Request $request)
	{
		$list = Admin::select('admin.*');

		if ($request->has('search')) {
			foreach ($request->get('search') as $key => $term) {
				if (array_key_exists($key, $this->search_rules)) {
					$search_rules = $this->search_rules[$key];

					if (array_key_exists('raw', $search_rules)) {
						$list = $list->whereRaw(str_replace('{value}', $term, $search_rules['raw']));
					} else {
						$list = $list->where($key, $search_rules['operator'], str_replace('{value}', $term, $search_rules['value']));
					}
				} else {
					$list = $list->where($key, $term);
				}
			}
		}

		$sort = json_decode($request->get('sort', false), true);

		if ($sort && isset($sort['predicate']) && isset($sort['reverse'])) {
			$list = $list->orderBy($sort['predicate'], $sort['reverse'] ? 'DESC' : 'ASC');
		} else {
			$list = $list->orderBy('admin.created_at', 'DESC');
		}

		return $list;
	}

}
