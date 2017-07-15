<?php namespace App\Http\Controllers\AdNetwork\Auth;

use App\AdNetwork;
use App\AdNetworkUser;
use App\Events\AdNetworkSubscriptionWasDone;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdNetwork\StoreAdNetworkRequest;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Validator;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	protected $redirectTo = '/';

	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->auth = Auth::ad_network();

		$this->middleware('ad_network.guest', ['except' => 'getLogout']);
	}

	/**
	 * Show the application login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLogin(Request $request)
	{
		if ($request->has('token')) {
			$token = $request->get('token');

			return view('ad_network.auth.login', compact('token'));
		}

		return view('ad_network.auth.login');
	}

	public function postLogin(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email', 'password' => 'required',
		]);

		$credentials = $request->only('email', 'password');

		if ($this->auth->attempt($credentials, $request->has('remember')))
		{
			if ($this->auth->get()->adNetwork->status === AdNetwork::STATUS_PENDING) {
				$this->auth->logout();
				return response()->json(['forbiddens' => $this->getFailedStatusMessage()], 403);
			}

			if ($request->ajax()) {
				$intended_url = session()->get('url.intended', $this->redirectPath());
				session()->forget('url.intended');

				return response()->json(['redirect' => $intended_url]);
			}

			return redirect()->intended($this->redirectPath());
		}

		if ($request->ajax()) {
			return response()->json(['email' => $this->getFailedLoginMessage()], 422);
		}

		return redirect($this->loginPath())
			->withInput($request->only('email', 'remember'))
			->withErrors([
				'email' => $this->getFailedLoginMessage(),
			]);
	}

	public function getRegister(Request $request)
	{
		$email = $request->get('email');

		return view('ad_network.auth.register', compact('email'));
	}

	public function postRegister(StoreAdNetworkRequest $request)
    {
    	$ad_network = new AdNetwork();
    	$ad_network->fill($request->all());
    	$ad_network->status = AdNetwork::STATUS_PENDING;

    	if (!$ad_network->save()) {
    		return response()->json([$this->getErrorMessage()], 422);
    	}

    	$referent = AdNetworkUser::create([
    		'name' => $request->get('referent_name'),
    		'family_name' => $request->get('family_name'),
    		'email' => $request->get('referent_email'),
    		'title' => $request->get('title'),
    		'phone' => $request->get('phone'),
    		'password' => $request->get('password'),
    		'ad_network_id' => $ad_network->id
    	]);

    	$ad_network->ad_network_user_id = $referent->id;

    	if (!$referent->save() || !$ad_network->save()) {
    		return response()->json([$this->getErrorMessage()], 422);
    	}

    	event(new AdNetworkSubscriptionWasDone($ad_network, $referent));

		return response()->json(compact('ad_network', 'referent'));
    }

	/**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        $this->auth->logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

	/**
	 * Get the failed login message.
	 *
	 * @return string
	 */
	protected function getFailedLoginMessage()
	{
		return 'Aucun utilisateur correspondant.';
	}

	/**
	 * Get the failed status message.
	 *
	 * @return string
	 */
	protected function getFailedStatusMessage()
	{
		return 'Votre compte n\'a pas encore été confirmé par Mediaresa.';
	}

	protected function getErrorMessage()
	{
		return "Une erreur est survenue. Veuillez réessayer.";
	}

}
