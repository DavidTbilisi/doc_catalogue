<?php

namespace App\Http\Controllers\Administration;

use App\Models\Group;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{


    public function index($id=null)
    {
        if ($id != null) {
            $group = Group::with("users")->where("id", $id)->firstOrFail();
            return view("admin.group", compact('group', $group));
        } else {
            $groups = Group::with("users")->get();
            return view('admin.group_list', compact('groups',$groups));
        }
    }



    public function create()
    {
        return view('admin.group_add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'alias' => 'required|max:200',
            'description' => 'required',
        ]);

        $g = new Group();
        $g->name = strtolower($request->alias);
        $g->alias = ucfirst($request->alias);
        $g->description = $request->description;
        $g->created_at = now();
        $g->updated_at = now();
        $g->save();

        return redirect(route("groups.index"))->with("message", "ჯგუფი წარმატებით დაემატა");
    }


    public function show($id)
    {
    }


    public function edit($id)
    {
        $group = Group::with("permissions")->where("id", $id )->firstOrFail();
        $permissions = Permission::all();
        $gp = [];
        foreach ($group->permissions as $perm):
            $gp[$perm->name] = $perm->id;
        endforeach;

        return view("admin.group", ['group' => $group, 'gp'=>collect($gp),'permissions' => $permissions]);
    }


    public function update(Request $request, $id){


       DB::transaction(function() use ($request, $id){


           $g = Group::findOrFail($id);
           $g->name = strtolower($request->alias);
           $g->alias = ucfirst($request->alias);
           $g->description = $request->description;
           $g->created_at = now();
           $g->updated_at = now();
           $g->save();
//
           DB::table("group_permission")->where('group_id', '=', $id)->delete();

            foreach($request->permissions as $permission_id):
                DB::table("group_permission")->insert([
                    "group_id" => $id,
                    "permission_id" => $permission_id,
                    "updated_at" => now(),
                ]);
            endforeach;


        });
        return redirect(route("groups.edit", ['id'=>$id]))->with("message", "ჯგუფი წარმატებით დარედაქტირდა");

    }

    public function destroy($id)
    {
        DB::transaction(function() use ( $id){
            DB::table("group_permission")->where('group_id', '=', $id)->delete();
            DB::table('groups')->where('id', '=', $id)->delete();
        });

        return redirect(route("groups.index"));
    }
}
