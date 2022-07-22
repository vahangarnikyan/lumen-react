<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Facades\Options;
use App\Facades\Geoip;
use Carbon\Carbon;
use App\Post;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        // $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('front.index', [
            // 'page' => $page
        ]);
    }

    public function chat() {
        $page = Post::where('slug', 'chat')->where('post_type', Post::$POST_TYPE_PAGE)->first();
        return view('front.chat', [
            'page' => $page,
        ]);
    }
}
