<?php namespace App\Http\Controllers\Main\Auth;

use App\Events\BuyerPasswordResetWasAsked;
use App\Events\BuyerPasswordResetWasDone;
use App\Http\Controllers\Controller;
use App\Http\Requests\Main\ResetPasswordRequest;
use App\User;
use Auth;
use DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Mail;
use Ramsey\Uuid\Uuid;
use Route;

class PasswordController extends Controller {
	/**
	 * Send a reset link to the given user.
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function postResetEmail(ResetPasswordRequest $request)
	{
		$email = $request->get('email');

		try {
			$user = User::where('email', $email)->firstOrFail();
		} catch (ModelNotFoundException $e) {
			return response()->json(["email" => $this->getUserRecoveryFailureMessage()], 422);
		}

		$token = substr(Uuid::uuid4()->toString(), 0, 10);
		$reset_path = route('main.password.form.reset') . '?token=' . $token . '#new_password';

		$this->storeResetPassword($email, $token);

		event(new BuyerPasswordResetWasAsked($user, $reset_path));

		return response()->json(['success' => 'Demande de réinitialisation de mot de passe effectuée avec succès.']);
	}

	public function getReset(Request $request)
	{
		$token = $request->get('token', null);

		return view('main.index')->with(compact('token'));
	}

	/**
	 * Reset user password and update it
	 *
	 * @param  ResetPasswordRequest $request
	 * @return Response
	 */
	public function postReset(ResetPasswordRequest $request)
	{
		$token = $request->get('token', null);
		$email = $request->get('email', null);
		$password = $request->get('password', null);

		if (is_null($token) || is_null($email) || is_null($password)) {
			return response()->json(["data missing : " . $request->get('token', 'inexistant')], 422);
			return response()->json([$this->getErrorMessage()], 422);
		}

		$reset_exists = DB::select('select * from password_resets where email = :email and token = :token',	['email' => $email, 'token' => $token]);

		if (empty($reset_exists)) {
			return response()->json([$this->getUnauthorizedMessage()], 403);
		}

		try {
			$user = User::where('email', $email)->firstOrFail();
		} catch (ModelNotFoundException $e) {
			return response()->json(["email" => $this->getUserRecoveryFailureMessage()], 422);
		}

		$user->password = $password;

		if ($user->save()) {
			event(new BuyerPasswordResetWasDone($user));

			return response()->json(['success' => $this->getResetSuccessMessage()]);
		}

		return response()->json([$this->getErrorMessage()], 422);
	}

	/**
	 * Store a newly reset password request
	 *
	 * @param String $email
	 * @param String $token
	 * @return void
	 */
	protected function storeResetPassword($email, $token)
	{
		$query = DB::select('select * from password_resets where email = :email', ['email' => $email]);
		$reset_exists = empty($query) ? false : true;

		if (!$reset_exists) {
			DB::insert('INSERT INTO password_resets (email, token)
						VALUES (:email, :token)', [
							'email' => $email,
							'token' => $token,
						]);
		} else {
			DB::update('update password_resets set token = :token where email = :email', ['token' => $token, 'email' => $email]);
		}
	}

	protected function getUserRecoveryFailureMessage()
	{
		return "Aucun utilisateur existant pour l'email donné";
	}

	protected function getUnauthorizedMessage()
	{
		return "Accès refusé. Veuillez reformuler une réinitialisation de mot de passe.";
	}

	protected function getErrorMessage()
	{
		return "Une erreur est survenue. Veuillez réessayer.";
	}

	protected function getResetSuccessMessage()
	{
		return "Nouveau mot de passe sauvegardé.";
	}
}
