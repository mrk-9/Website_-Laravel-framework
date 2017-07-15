<?php namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests;

class NewsController extends Controller {

public function getIndex()
{
  return view('main.news.index');
}

public function getShow($id)
{
  return view('main.news.show');
}

}
