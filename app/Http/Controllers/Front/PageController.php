<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;

use App\Facades\Utils;

use App\Post;

use DB;

class PageController extends Controller
{
  public function show($slug)
  {
    $page = Post::where('slug', $slug)->get();
    
    if (sizeof($page) > 0) {
      return view('front.page', [
        'page' => $page[0]
      ]);
    } else {
      return redirect(Utils::localeUrl('/'));
    }
  }
}
