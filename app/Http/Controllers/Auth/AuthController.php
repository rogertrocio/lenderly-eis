<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('guest', only: ['login']),
            new Middleware('auth', only: ['logout', 'checkIn', 'checkOut']),
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

    /**
     * Check in attendance of the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250601 - Created
     */
    public function checkIn(): JsonResponse
    {
        $user = auth()->user();

        abort_if(
            $user->attendances()
                ->where('date', now()->toDateString())
                ->exists(),
            422,
            'You are already checked in today.'
        );

        $attendance = $user->attendances()->create([
            'date' => now()->toDateString(),
            'check_in' => now(),
            'is_active' => true
        ]);

        return response()->json(['data' => $attendance], 200);
    }

    /**
     * Check out attendance of the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250601 - Created
     */
    public function checkOut(): JsonResponse
    {
        $user = auth()->user();

        abort_if(
            $user->attendances()
                ->where('date', now()->toDateString())
                ->whereNotNull(['check_in', 'check_out'])
                ->exists(),
            422,
            'You are already checked out today.'
        );

        abort_if(
            !$user->hasActiveAttendance(),
            422,
            'You haven’t checked in yet today.'
        );

        $attendance = $user->attendances()->where('is_active', true)->firstOrFail();

        $attendance->update(['check_out' => now(), 'is_active' => false]);

        $attendance->refresh();

        return response()->json(['data' => $attendance], 200);
    }
}
