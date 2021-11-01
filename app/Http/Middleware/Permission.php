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
        $user = User::with('group')
            ->with('permissions')
            ->with('routes')
            ->where('id',Auth::id())
            ->first();


        $routePerms = Authroutes::with('users')->where('url',$request->route()->uri())->first();
        $userPerms = false;
        $groupPerm = false;

        dump($routePerms);

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
            return redirect(route("welcome"))
                ->with(
                    "message",
                    "You have not permission to view {$request->route()->uri}"
                );
        }

        return $next($request);
    }
}
