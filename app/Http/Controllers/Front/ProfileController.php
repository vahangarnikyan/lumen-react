<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use Symfony\Component\HttpFoundation\Cookie;
use Carbon\Carbon;
use App\Facades\Utils;
use App\Mails\ConfirmationMail;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('redirectIfUnconfirmed');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $date = new Carbon();

        return view('front.profile', [
            'voter' => $user
        ]);
    }

    public function pendingConfirmation()
    {
        return view('front.pending-confirmation');
    }

    public function sendConfirmation(Request $request)
    {
        $user = $request->user();
        Mail::to($user)->send(new ConfirmationMail($user));
        return redirect(Utils::localeUrl('pending-confirmation'));
    }

    public function confirm(Request $request)
    {
        $user = $request->user();
        if ($user->isValidConfirmCode($request->input('code'))) {
            $user->update(['confirmed' => true]);
            return redirect(Utils::localeUrl('profile'));
        }
        return redirect(Utils::localeUrl('pending-confirmation'));
    }

    public function logout()
    {
        $tokenCookie = new Cookie('token', '', time() - 60 * 60 * 48);
        return response(view('front.redirect', [
            'url' => Utils::localeUrl('/')
        ]))->header('Set-Cookie', $tokenCookie->__toString());
    }
}
