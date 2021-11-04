<?php

namespace App\Http\Middleware;

use App\Models\Authroutes;
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
        $user = User::with('group')->with('permissions')
            ->where('id',Auth::id())
            ->first();

        $userPerms = 1;
        $groupPerm = 1;


        foreach($user->permissions as $up) {
            if(!empty($up)) {
                $userPerms = $userPerms < $up->power? $up->power : $userPerms;
            }
        }

        foreach($user->group->permissions as $gp) {
            if(!empty($gp)) {
                $groupPerm = $groupPerm < $gp->power? $gp->power : $groupPerm;
            }
        }

        session()->put("perms", "{$userPerms},{$groupPerm}");

        if ($groupPerm == false && $userPerms == false) {
            return redirect(route("welcome"))
                ->with(
                    "message",
                    "You have not permission to view {$request->route()->uri}"
                );
        }

        return $next($request);
    }
}
