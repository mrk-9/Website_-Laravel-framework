<?php namespace App\Http\Controllers\Main\Auth;

use App\Buyer;
use App\Events\BuyerSubscriptionWasDone;
use App\Http\Controllers\Controller;
use App\Http\Requests\Main\CreateBuyerRequest;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use URL;
use Validator;

class AuthController extends Controller
{

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
        $this->auth = Auth::user();

        $this->middleware('user.guest', ['except' => 'getLogout']);
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $errors = [];

        if ($this->auth->attempt($credentials, $request->has('remember'))) {
            $user = $this->auth->get();
            $user->load('buyer');
            if ($user->buyer->status == 'valid') {

                if ($request->ajax()) {
                    $intended_url = session()->get('url.intended', $this->redirectPath());
                    session()->forget('url.intended');

                    return response()->json(['redirect' => $intended_url]);
                }
                return redirect()->intended($this->redirectPath());
            } else {
                $this->auth->logout();
                $errors['status'] = 'Votre compte n\'a pas encore été confirmé par Mediaresa.';
            }

        } else {
            $errors['email'] = 'Aucun utilisateur correspondant.';
        }

        if ($request->ajax()) {
            return response()->json($errors, 422);
        }

        return redirect($this->loginPath())
            ->withInput($request->only('email', 'remember'))
            ->withErrors($errors);
    }

    public function postSignup(Request $request)
    {
        if ($request->get('buyer_type') === "ad_network") {
            return redirect()->to(route('ad-network.auth.register'));
        }

        $user = [
            'buyer' => [
                'type' => $request->get('buyer_type'),
            ]
        ];

        return view('main.auth.signup', compact('user'));
    }

    public function postSignupForm(CreateBuyerRequest $request, Buyer $buyer, User $user)
    {
        $user->fill($request->all());

        $buyer->fill([
            'type' => $request->get('buyer_type'),
            'name' => $request->get('buyer_name'),
            'company_type' => $request->get('buyer_company_type'),
            'activity' => $request->get('buyer_activity'),
            'address' => $request->get('buyer_address'),
            'zipcode' => $request->get('buyer_zipcode'),
            'city' => $request->get('buyer_city'),
            'phone' => $request->get('buyer_phone'),
            'email' => $request->get('buyer_email'),
            'customers' => $request->get('buyer_customers')
        ]);

        if ($buyer->type ==  Buyer::TYPE_AGENCY) {
            $buyer->activity = null;
        } else {
            $buyer->customers = null;
        }

        $buyer->save();
        $user->fill([
            'buyer_id' => $buyer->id,
        ]);
        $user->save();
        $buyer->user_id = $user->id;
        $buyer->save();

        event(new BuyerSubscriptionWasDone($buyer, $user));

        return response()->json(compact('user', 'buyer', 'success'));
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

    public function getLogin()
    {
        return redirect(URL::previous() . '#login');
    }
}
