<?php

namespace App\Http\Controllers\Administration;

use App\Models\Group;
use App\Models\User;
use App\Http\Controllers\Controller;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param null $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.user_add');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function creategroup()
    {
        return view('admin.group_add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            redirect(route("users"))->with("message", "მომხმარებელი წარმატებით დაემატა");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storegroup(Request $request)
    {
        $request->validate([
            'alias' => 'required|unique:groups|max:200',
            'description' => 'required',
        ]);


        $g = new Group();
        $g->name = strtolower($request->alias);
        $g->alias = ucfirst($request->alias);
        $g->description = $request->description;
        $g->created_at = now();
        $g->updated_at = now();

        if ( $g->save() ) {
            redirect(route("groups"))->with("message", "ჯგუფი წარმატებით დაემატა");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }


    public function profile()
    {
        $user = User::with("group")->where("id", Auth::id() )->first();
        return view('admin.dashboard', compact('user',$user));
    }

    public function list()
    {
        $users = User::with("group")->get();
        return view('admin.user_list', compact('users',$users));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
