<?php namespace App\Http\Controllers\Main\Api;

use App\AdPlacement;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Main\SaveBuyerRequest;
use App\Selection;
use Illuminate\Support\Facades\Auth;

class SelectionController extends Controller
{

    public function postAdPlacementSelection(AdPlacement $ad_placement)
    {
        $user = Auth::user()->get();
        if (Selection::where("ad_placement_id", $ad_placement->id)->where("user_id", $user->id)->get()->isEmpty()) {
            $selection = new Selection();
            $selection->fill([
                'user_id' => $user->id,
                'ad_placement_id' => $ad_placement->id
            ]);
            $selection->save();
            return response()->json('Selection sauvegardée avec succès');
        } else {
            return response()->json(["selection" => "Il existe déjà une selection pour cet emplacement"], 422);
        }
    }

    public function deleteAdPlacementSelection(AdPlacement $ad_placement)
    {
        $user = Auth::user()->get();
        $selection = Selection::where("ad_placement_id", $ad_placement->id)->where("user_id", $user->id)->get()->first();
        if ($selection != null) {
            $selection->delete();
            return response()->json('Selection supprimée avec succès\'');
        }
        return response()->json(["selection" => 'Aucune selection n\'existe pour cet emplacement'], 422);

    }

}
