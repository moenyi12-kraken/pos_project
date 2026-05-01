<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('authentication.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        $request->session()->regenerate();

        if (! $user) {
            return redirect('/');
        }

        if ($user->role === 'superadmin' || $user->role === 'admin') {

            return redirect()->intended(route('adminHome', absolute: false));

            // return redirect()->route('adminHome');
            // return to_route('adminHome');
        }
        return redirect()->intended(route('userHome', absolute: false));

        // return redirect()->route('userHome');
        // return to_route('userHome');

        // return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // return redirect('/');
        return to_route('login');
    }
}
