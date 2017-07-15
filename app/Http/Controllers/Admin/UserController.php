<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if ($request->ajax()) {
			$users = User::search($request->all(), ['buyer.referent']);

			return response()->json($users);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param User $user
	 * @return Response
	 */
	public function destroy(User $user)
	{
		$user->delete();

		return response()->json(['id' => $user->id]);
	}

	/**
	 * Update the specified resource from storage.
	 *
	 * @param UpdateUserRequest $request
	 * @param User $user
	 * @return Response
	 */
	 public function update(UpdateUserRequest $request, User $user)
 	{
 		$user->fill($request->all());
 		$user->save();

 		return response()->json(compact('user'));
 	}
}
