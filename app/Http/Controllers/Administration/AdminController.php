<?php

namespace App\Http\Controllers\Administration;

use App\Models\Group;
use App\Models\User;
use App\Http\Controllers\Controller;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function index($id=null)
    {
        if($id!=null) {
            $user = User::with("group")->where("id", $id )->firstOrFail();
            return view('admin.user', compact('user',$user));
        } else {
            $users = User::with("group")->get();
            return view('admin.user_list', ['users'=>$users, ]);
        }
    }


    public function create()
    {
        return view('admin.user_add');
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
           return redirect(route("users"))->with("message", "მომხმარებელი წარმატებით დაემატა");
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
        return view('admin.user_list', compact('users',$users));
    }


    public function edit($id)
    {
        $user = User::with("group")
            ->with("permissions")
            ->where("id", $id )
            ->firstOrFail();
        $groups = Group::all();

        return view('admin.user', ['user' => $user, 'groups' => $groups]);
    }

    public function update(Request $request, $id)
    {
        $user = User::where("id", $id)->firstOrFail();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->group_id = $request->group_id;
        $user->save();
        return redirect(route("users"));
    }

    public function destroy($id)
    {
        //
    }
}
