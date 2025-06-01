<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['profile', 'update']),
        ];
    }

    /**
     * Get the profile of the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Contracts\View\View
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250601 - Created
     */
    public function profile(Request $request): JsonResponse|View
    {
        if ($request->wantsJson()) {
            return response()->json([
                'data' => auth()->user(),
            ], 200);
        }

        return view('app');
    }

    /**
     * Update the profile of the authenticated user.
     *
     * @param \App\Http\Requests\ProfileRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250601 - Created
     *  RAT 20250601 - Store avatar image and path.
     */
    public function update(ProfileRequest $request): JsonResponse
    {
        $user = auth()->user();

        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            if (!is_null($user->avatar)) Storage::delete($user->avatar);

            $data['avatar'] = $request->file('avatar')->store('avatars');
        }

        if (is_null($request->avatar)) {
            unset($data['avatar']);
        }

        $user->update($data);

        return response()->json([
            'data' => $user,
        ], 200);
    }
}
