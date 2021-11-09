<?php

namespace App\Http\Controllers\Administration;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index($id = null)
    {
        if ($id != null) {
            $permission = Permission::where("id", $id)->firstOrFail();
            return view("admin.permission", compact('permission', $permission));
        } else {
            $permissions = Permission::all();
            return view("admin.permission_list", compact('permissions', $permissions));
        }
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions|max:200',
            'description' => 'required',
            'power' => 'required',
        ]);

        $p = new Permission();
        $p->name = $request->name;
        $p->description = $request->description;
        $p->power = $request->power;
        $p->created_at = now();
        $p->updated_at = now();
        $p->save();
        return redirect(route("permissions.index"));
    }

    public function show($id)
    {
        $permission = Permission::where("id", $id)->firstOrFail();
        return view("admin.permission", compact('permission', $permission));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
