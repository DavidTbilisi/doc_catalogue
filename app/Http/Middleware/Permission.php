<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::with('group')
            ->with('permissions')
            ->where('id',Auth::id())
            ->first();

        $userPerms = false;
        $groupPerm = false;


        foreach ($user->permissions as $permission) {
            if ($permission->power <= 3) {
                $userPerms = True;
            }
        }
        foreach ($user->group->permissions as $permission) {
            if ($permission->power <= 1) {
                $groupPerm = True;
            }
        }


        if ($groupPerm == false && $userPerms == false) {
//            dd($request->route());
            return redirect(route("welcome"))->with("message", "You have not permission to view {$request->route()->uri}");
        }

        return $next($request);
    }
}
