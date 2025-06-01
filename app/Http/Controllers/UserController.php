<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\View\View
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250601 - Created
     */
    public function index(Request $request): AnonymousResourceCollection|View
    {
        $users = User::commonFilters($request->only('filter'))
            ->with('latestAttendance')
            ->orderBy('last_name')
            ->paginate(8);

        if ($request->wantsJson()) {
            return UserResource::collection($users);
        }

        return view('app');
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param string|integer $id
     * @return \App\Http\Resources\UserResource|\Illuminate\Contracts\View\View
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250602 - Created
     */
    public function show(Request $request, string|int $id): UserResource|View
    {
        $user = User::findOrFail($id);

        if ($request->wantsJson()) {
            return new UserResource($user);
        }

        return view('app');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UserRequest $request
     * @param string|integer $id
     * @return \App\Http\Resources\UserResource
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250602 - Created
     */
    public function update(UserRequest $request, string|int $id): UserResource
    {
        $user = User::findOrFail($id);

        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            if (!is_null($user->avatar)) Storage::delete($user->avatar);

            $data['avatar'] = $request->file('avatar')->store('avatars');
        }

        if (is_null($request->avatar)) {
            unset($data['avatar']);
        }

        $user->update($data);

        $user->refresh();

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250601 - Created
     */
    public function destroy(User $user): Response
    {
        $user->delete();

        return response()->noContent();
    }
}
