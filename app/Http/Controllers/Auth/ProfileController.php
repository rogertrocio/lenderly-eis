<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\UserResource;
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
     * @return \App\Http\Resources\UserResource|\Illuminate\Contracts\View\View
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250601 - Created
     *  RAT 20250602 - Return an UserResource response.
     */
    public function profile(Request $request): UserResource|View
    {
        if ($request->wantsJson()) {
            $user = auth()->user();

            return new UserResource($user->load('roles', 'roles.permissions', 'latestAttendance'));
        }

        return view('app');
    }

    /**
     * Update the profile of the authenticated user.
     *
     * @param \App\Http\Requests\ProfileRequest $request
     * @return \App\Http\Resources\UserResource
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250601 - Created
     *  RAT 20250601 - Store avatar image and path.
     *  RAT 20250602 - Return an UserResource response.
     */
    public function update(ProfileRequest $request): UserResource
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

        return new UserResource($user);
    }
}
