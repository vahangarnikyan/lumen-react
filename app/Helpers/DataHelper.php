<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Hash;

use App\Menu;
use App\User;
use App\Option;
use App\Facades\Geoip;

use DB;

class DataHelper
{
    public function getMenus($menu_type = 'header')
    {
        return Menu::where('parent_id', 0)->where('menu_type', $menu_type)->orderBy('display_order')->get();
    }

    public function getUser()
    {
        if (Auth::check()) {
            return Auth::user();
        }

        $ip = Request::ip();
        $user = User::where('username', $ip)->first();

        if ($user === null) {
            $password = (new Carbon())->getTimestamp();
            $user = User::create([
                'firstname' => $ip,
                'lastname' => $ip,
                'secondname' => $ip,
                'username' => $ip,
                'email' => "{$ip}@ganagratis.com",
                'password' => Hash::make($password),
                'role' => User::$ROLE_UNKNOWN
            ]);
        }
        return $user;
    }

    public function getFooter()
    {
        $footer = Option::where('name', 'footer')->get();

        if (sizeof($footer) > 0) {
            return $footer[0]->value;
        } else {
            return '';
        }
    }
}
