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


        DB::transaction(function($request) {




            $g = new Group();
            $g->name = strtolower($request->alias);
            $g->alias = ucfirst($request->alias);
            $g->description = $request->description;
            $g->created_at = now();
            $g->updated_at = now();

            if ( $g->save() ) {
                return redirect(route("groups"))->with("message", "ჯგუფი წარმატებით დაემატა");
            }
        });


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


           $g = Group::find($id);
           $g->name = strtolower($request->alias);
           $g->alias = ucfirst($request->alias);
           $g->description = $request->description;
           $g->created_at = now();
           $g->updated_at = now();

           DB::table("group_permission")->where('group_id', '=', $id)->delete();
            foreach($request->permissions as $permission_id):
                DB::table("group_permission")->insert([
                    "group_id" => $id,
                    "permission_id" => $permission_id
                ]);
            endforeach;



            if ( $g->save() ) {
                return redirect(route("groups"))->with("message", "ჯგუფი წარმატებით დარედაქტირდა");
            }
        });
        return redirect(route("editgroup", ['id'=>$id]))->with("message", "ჯგუფი წარმატებით დარედაქტირდა");

    }

    public function destroy($id)
    {
        //
    }
}
