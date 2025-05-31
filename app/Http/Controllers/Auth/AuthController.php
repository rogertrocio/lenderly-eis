<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', only: ['login']),
            new Middleware('auth', only: ['logout']),
        ];
    }

    /**
     * Redirect to login page.
     *
     * @return \Illuminate\Contracts\View\View
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250531 - Created
     */
    public function login(): View
    {
        return view('app');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param \App\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250531 - Created
     */
    public function authenticate(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect('/');
        };

        return response()->json([
        'errors' => [
            'email' => ['The provided credentials do not match our records.'],
            ],
        ], 422);
    }

    /**
     * Log the user out of the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250531 - Created
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
