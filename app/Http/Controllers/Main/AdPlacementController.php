<?php namespace App\Http\Controllers\Main;

use App\Frequency;
use App\Http\Controllers\Controller;
use App\AdPlacement;
use App\Support;
use App\Format;
use App\BroadcastingArea;
use App\Target;
use App\TechnicalSupport;
use App\Template;
use App\Theme;
use App\Category;
use App\SupportType;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class AdPlacementController extends Controller {

  const PAGINATOR_NB_BY_PAGE = 10;

  public static function getBudgets() {
    return Collection::make(
        [
            ['slug'=>'0|2000', 'name'=>'moins de 2 000€'],
            ['slug'=>'2000|5000', 'name'=>'entre 2 000€ et 5 000€'],
            ['slug'=>'5000|10000', 'name'=>'entre 5 000€ et 10 000€'],
            ['slug'=>'10000|20000', 'name'=>'entre 10 000€ et 20 000€'],
            ['slug'=>'20000', 'name'=>'plus de 20 000€'],
        ]
    );
  }

  public function getIndex(Request $request)
  {
    $withoutAll = function ($var) {
      return(!($var == "all" ||  is_null($var)));
    };

    $selected = $request->only('support', 'category', 'frequency', 'target', 'theme', 'broadcasting_area', 'format', 'budget', 'type', 'search_sort');
    $selected = array_filter($selected, $withoutAll);

    $supports = Support::all()->sortBy("name")->toArray();
    $categories = Category::all()->sortBy("name")->toArray();
    $frequencies = Frequency::all()->sortBy("id")->toArray();
    $targets = Target::all()->sortBy("name")->toArray();
    $themes = Theme::all()->sortBy("name")->toArray();
    $broadcasting_areas = BroadcastingArea::all()->sortBy("id")->toArray();
    $formats = Format::all()->sortBy("name")->toArray();
    $budgets = self::getBudgets();
    $types = AdPlacement::getTypes();

    $query = AdPlacement::with(
        'media.support',
        'media.category',
        'media.targets',
        'media.theme',
        'media.broadcastingArea',
        'media.frequency',
        'format'
    )->select('ad_placement.*');
    $query->leftjoin('media', 'media_id', '=', 'media.id');

    $query->where('ending_at', '>', Carbon::now());
    $query->where('starting_at', '<=', Carbon::now());

    if(array_key_exists('support', $selected)) {
      $query->leftjoin('support', 'media.support_id', '=', 'support.id');
      $query->where('support.slug', $selected['support']);
    }
    if(array_key_exists('category', $selected)){
      $query->leftjoin('category', 'media.category_id', '=', 'category.id');
      $query->where('category.slug', $selected['category']);
    }
    if(array_key_exists('target', $selected)){
      $query->leftjoin('media_target', 'media_target.media_id', '=', 'media.id');
      $query->where(function ($query) use ($selected) {
        foreach($selected['target'] as $t) {
          $query->orWhere('media_target.target_id', $t);
        }
      });
    }
    if(array_key_exists('theme', $selected)){
      $query->join('theme', 'media.theme_id', '=', 'theme.id');
      $query->where('theme.slug', $selected['theme']);
    }
    if(array_key_exists('broadcasting_area', $selected)){
      $query->leftjoin('broadcasting_area', 'media.broadcasting_area_id', '=', 'broadcasting_area.id');
      $query->where('broadcasting_area.slug', $selected['broadcasting_area']);
    }
    if(array_key_exists('format', $selected)){
      $query->leftjoin('format', 'ad_placement.format_id', '=', 'format.id');
      $query->where('format.slug', $selected['format']);
    }
    if(array_key_exists('budget', $selected)){
      $budget = explode('|', $selected['budget']);
      if(isset($budget[0])) {
        $query->where('ad_placement.price', '>=', $budget[0]);
      }
      if(isset($budget[1])) {
        $query->where('ad_placement.price', '<=', $budget[1]);
      }
    }
    if(array_key_exists('type', $selected)) {
      $query->where(function ($query) use ($selected) {
        $query->where('type', $selected['type']);
        if($selected['type'] == AdPlacement::TYPE_HYBRID) {
          $query->orWhere('type', AdPlacement::TYPE_BOOKING);
        }
      });
    }
    if(array_key_exists('search_sort', $selected) && ($selected['search_sort'] === "asc_price" || $selected['search_sort'] === "desc_price")) {
      if($selected['search_sort'] === "asc_price") {
        $query->orderBy('ad_placement.price', 'ASC');
      } else {
        $query->orderBy('ad_placement.price', 'DESC');
      }
    } else {
      $query->orderBy('ad_placement.ending_at', 'ASC');
    }

    $paginator = $query->paginate(self::PAGINATOR_NB_BY_PAGE);

    $paginator->appends($request->all());

    return view('main.ad_placement.index', compact('supports', 'categories', 'frequencies', 'targets', 'themes', 'broadcasting_areas', 'formats', 'budgets', 'types', 'selected', 'paginator'));
  }

  public function getShow(AdPlacement $ad_placement)
  {
    $ad_placement->load(
        'media.support',
        'media.category',
        'media.targets',
        'media.theme',
        'media.broadcastingArea',
        'format'
    );

    if(Auth::user()->check()) {
      $credit_card = Auth::user()->get()->buyer->credit_card;
    }

    $technicalSupports = TechnicalSupport::all();
    $templates = Template::all();

    return view('main.ad_placement.show', compact('ad_placement', 'technicalSupports', 'templates', 'credit_card'));
  }

}
