<?php namespace App\Http\Controllers\Main\Api;

use App\AdPlacement;
use App\Events\AdPlacementWasShared;
use App\Http\Controllers\Controller;
use App\Http\Requests\Main\ShareAdPlacementRequest;
use Illuminate\Support\Facades\Auth;

class AdPlacementController extends Controller {

    public function get(AdPlacement $adPlacement)
    {
        $adPlacement->load('media.support',
            'media.category',
            'media.targets',
            'media.theme',
            'media.broadcastingArea',
            'media.frequency',
            'format');

        if(!Auth::user()->check()) {
            $adPlacement->minimum_price = null;
            $adPlacement->price = null;
        }

        return response()->json($adPlacement);
    }

    public function share(AdPlacement $adPlacement, ShareAdPlacementRequest $request)
    {
        event(new AdPlacementWasShared($adPlacement, Auth::user()->get(), $request->get('email'), $request->get('message')));
        return response()->json("ok");
    }

}
