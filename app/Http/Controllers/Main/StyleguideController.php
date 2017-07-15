<?php namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\AdPlacement;
use App\TechnicalSupport;
use App\Template;

class StyleguideController extends Controller {

  public function getStyleguide()
  {
    $ad_placements = AdPlacement::all();

    $technicalSupports = TechnicalSupport::all();
    $templates = Template::all();

    return view('main.styleguide', compact('ad_placements', 'technicalSupports', 'templates'));
  }

}
