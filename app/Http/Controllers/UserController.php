<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

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
