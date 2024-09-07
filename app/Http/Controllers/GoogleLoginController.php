<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ProvidersRouteServiceProvider;
use Illuminate\Support\Facades\Session;

class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->email)->first();
        if(!$user)
        {
            // Check Email sismedika
            $emailDomain = explode('@', $googleUser->email)[1];
            if ($emailDomain != 'sismedika.com') {
                Session::flash('message', 'Lu siapa anjir? ini cuman buat akun sismedika.com');
                return redirect('login');
            }

            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                // 'password' => \Hash::make(rand(100000,999999))
                'email_verified_at' => now()
            ]);
        }

        Auth::login($user);

        return redirect(route('dashboard'));
    }
}
