<?php namespace App\Http\Controllers\AdNetwork;

use App\Acquisition;
use App\AdPlacement;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;

class AdPlacementEarnedController extends Controller {

	public function index(Request $request)
	{
		if ($request->ajax()) {
			$query = AdPlacement::getBaseQuery()
				->leftJoin('acquisition', 'acquisition.ad_placement_id', '=', 'ad_placement.id')
				->whereNull('media.deleted_at')
				->whereNull('acquisition.deleted_at')
				->where(function($query) {
					$query->where('acquisition.charge_status', Acquisition::CHARGE_SUCCESS)
						->orWhere('acquisition.transfer_status', Acquisition::TRANSFER_SUCCESS);
				});

			$ad_placements = AdPlacement::search($request->all(), ['media.support', 'format'], $query);

			$ad_placements->each(function($ad_placement) {
				$ad_placement->append('winner');
				$ad_placement->winner->load('user.buyer');
			});

			return response()->json($ad_placements);
		}

		return view('ad_network.ad_placement_earned.index');
	}
}


