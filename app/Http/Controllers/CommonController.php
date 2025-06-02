<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommonController extends Controller
{
    /**
     * Get the collection roles.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250602 - Created
     */
    public function getRoles(): AnonymousResourceCollection
    {
        $roles = Role::orderBy('name')->get();

        return RoleResource::collection($roles);
    }
}
