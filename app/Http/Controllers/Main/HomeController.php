<?php namespace App\Http\Controllers\Main;

use App\AdPlacement;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Carbon\Carbon;

class HomeController extends Controller
{

    public function getIndex()
    {
        $query = AdPlacement::with(
            'media.support',
            'media.category',
            'media.targets',
            'media.theme',
            'media.broadcastingArea',
            'media.frequency',
            'format'
        );
        $query->where('ending_at', '>', Carbon::now());
        $query->where('starting_at', '<=', Carbon::now());
        $ad_placements = $query->orderBy('created_at', 'DESC')
            ->take(6)
            ->get();

        return view('main.index', compact('ad_placements'));
    }

}

