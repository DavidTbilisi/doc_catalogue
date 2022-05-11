<?php

namespace App\Http\Controllers\Administration;

use App\Facades\Perms;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Io;
use App\Models\Io_type;
use App\Models\IoGroupsPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FondPermsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 1;
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

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $io_item =  IO::with("permissions")
            ->with("type")
            ->where('id',$id)
            ->first();

        $perms = [];


        $groups_permission = $io_item->permissions;
        foreach ($groups_permission as $permission):
            $group = Group::find($permission->groups_id);
            $temp = Perms::fondPerms($permission->permission);

            $temp["group"] = $group;
            $perms[] = $temp;
        endforeach;


        return view('admin.io.io_permissions', [
            "permissions"=>$perms,
            "io"=>$io_item,
        ]);

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
    public function update(Request $request, $group_id, $io_id)
    {
        $igp = IoGroupsPermissions::where("groups_id",$group_id)->where("io_id", $io_id)->first();
        $powers = array_keys($request->except(['_token']));
        $all_power = array_sum($powers);

        $igp->permission = $all_power;
        $igp->save();

        return redirect(route("io_perms.show",['id'=>$io_id]));
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
