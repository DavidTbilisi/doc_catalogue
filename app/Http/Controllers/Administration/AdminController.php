<?php

namespace App\Http\Controllers\Administration;

use App\Models\User;
use App\Http\Controllers\Controller;
use Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return view('admin.user_list', compact('users',$users));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
