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
            ->with('routes')
            ->where('id',Auth::id())
            ->first();


//        $routePerms = Authroutes::with('users')->where('url',$request->route()->uri())->first();
        $userPerms = false;
        $groupPerm = false;


        foreach ($user->routes as $routes) {
            if($routes->url == $request->route()->uri() ){
//                $request->isMethod('post')
                dump($routes->pivot->permission_id);
                $userPerms = True;
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
