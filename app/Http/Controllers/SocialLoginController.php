<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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

        //Validation for error showing about existing acc and unique email
        $user = User::where('email', $socialLoginData->email)->first();

        if ($user) {
            if ($user->provider != $provider) {
                Session::put('emailExist', 'This email is already taken. You can only use one email per account.');
                return to_route('login');
            }
        }

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
