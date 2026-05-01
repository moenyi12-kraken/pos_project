<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $socialLoginData = Socialite::driver($provider)->user();
        // $user->token

        $socialUser = User::updateOrCreate([
            'provider_id' => $socialLoginData->id,
        ], [
            'name'           => $socialLoginData->name,
            'email'          => $socialLoginData->email,
            'nickname'       => $socialLoginData->nickname,
            'provider'       => $provider,
            'provider_id'    => $socialLoginData->id,
            'provider_token' => $socialLoginData->token,
        ]);

        Auth::login($socialUser);

        // return redirect('/dashboard');
        return to_route('userHome');
    }
}
