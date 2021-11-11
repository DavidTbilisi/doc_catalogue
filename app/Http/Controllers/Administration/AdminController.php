<?php

namespace App\Http\Controllers\Administration;

use App\Models\Group;
use App\Models\Permission;
use App\Models\User;
use App\Http\Controllers\Controller;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function index($id=null)
    {
        if($id!=null) {
            $user = User::with("group")->where("id", $id )->firstOrFail();
            return view('admin.users.user', compact('user',$user));
        } else {
            $users = User::with("group")->get();
            return view('admin.users.user_list', ['users'=>$users, ]);
        }
    }


    public function create()
    {
        return view('admin.users.user_add');
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|max:200',
            'password' => 'required',
        ]);


        $u = new User();
        $u->name = $request->name;
        $u->group_id = 3;
        $u->email = $request->email;
        $u->password= Hash::make($request->password);
        $u->active = "not active";
        $u->created_at = now();
        $u->updated_at = now();

        if ( $u->save() ) {
           return redirect(route("users.index"))->with("message", "მომხმარებელი წარმატებით დაემატა");
        }
    }



    public function show($id)
    {

    }


    public function profile()
    {
        $user = User::with("group")->where("id", Auth::id() )->first();
        return view('admin.profile', compact('user',$user));
    }

    public function list()
    {
        $users = User::with("group")->get();
        return view('admin.users.user_list', compact('users',$users));
    }


    public function edit($id)
    {
        $user = User::with("group")
            ->with("permissions")
            ->where("id", $id )
            ->firstOrFail();
        $groups = Group::all();
        $permissions = Permission::all();


        $up = [];
        foreach ($user->permissions as $perm):
            $up[$perm->name] = $perm->id;
        endforeach;


        return view('admin.users.user', ['user' => $user, 'groups' => $groups, 'permissions' => $permissions, 'up'=>collect($up)]);
    }

    public function update(Request $request, $id)
    {

        DB::transaction(function() use ($request, $id){

            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->group_id = $request->group_id;
            $user->save();

            DB::table("permission_user")->where('user_id', '=', $id)->delete();

            foreach($request->permissions as $permission_id):
                DB::table("permission_user")->insert([
                    "user_id" => $id,
                    "permission_id" => $permission_id,
                    "updated_at" => now(),
                ]);
            endforeach;

        });
        return redirect(route("users.index"));

    }

    public function destroy($id)
    {
        DB::transaction(function() use ( $id){
            DB::table("permission_user")->where('user_id', '=', $id)->delete();
            DB::table('users')->where('id', '=', $id)->delete();
        });

        return redirect(route("users.index"));
    }

}
