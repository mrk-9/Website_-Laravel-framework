<?php

namespace App\Http\Middleware\AdNetwork;

use Auth;
use Closure;
use Route;

class AdPlacementAuthorized
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct()
    {
        $this->auth = Auth::ad_network();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Authentication must be verified before
        // with an another middleware
        $ad_network_id = $this->auth->get()->ad_network_id;
        $ad_placement = $request->ad_placement;

        // The request must concern an ad placement
        if (!isset($ad_placement)) {
            return $next($request);
        }

        // The ad placement and the auth must belong
        // to the same ad network
        if ($ad_network_id !== $ad_placement->media->ad_network_id) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->back();
            }
        }

        return $next($request);
    }
}
