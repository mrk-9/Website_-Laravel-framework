<?php

namespace App\Http\Middleware\AdNetwork;

use App\Media;
use Auth;
use Closure;
use Route;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MediaAuthorized
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
        $media = null;
        if ($request->has('media_id')) {
            try {
                $media = Media::findOrFail($request->get('media_id'));
            } catch (ModelNotFoundException $e) {
                if ($request->ajax()) {
                    return response()->json('Not found', 404);
                }

                abort(404);
            }
        } else {
            $media = $request->media;
        }

        // The request must concern a media
        if (!isset($media)) {
            return $next($request);
        }

        // The media and the auth must belong
        // to the same ad network
        if ($ad_network_id !== $media->ad_network_id) {
            if ($request->ajax()) {
                return response('Unauthorized.', 403);
            } else {
                abort(404);
            }
        }

        return $next($request);
    }
}
