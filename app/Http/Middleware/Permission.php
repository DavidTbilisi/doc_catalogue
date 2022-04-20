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

        $userPerms = [];
        $groupPerms = [];


        foreach($user->permissions as $up) {
            if(!empty($up)) {
                $userPerms[$up->id] = $up->const_name;
            }
        }

        foreach($user->group->permissions as $gp) {
            if(!empty($gp)) {
                $groupPerms[$gp->id] = $gp->const_name;
            }
        }

        $all_permissions = $userPerms + $groupPerms;

        session()->put("perms", ["userPerms"=>$userPerms, "groupPerms"=>$groupPerms, 'perms'=> $all_permissions]);

        if (!count($all_permissions)) {
            return redirect(route("welcome"))
                ->with(
                    "message",
                    "You have not permission to view {$request->route()->uri}"
                );
        }

        return $next($request);
    }
}
