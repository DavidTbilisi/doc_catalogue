<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Io_type;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class IoTypesController extends Controller
{

    public function index()
    {
        $types = Io_type::all();
        return view("admin.iotypes.type_list", ['types'=>$types]);
    }


    public function create()
    {
        return view("admin.iotypes.add_type", ["type"]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|alpha|max:255',
            'field' => 'required',
            'type'  => 'required',
        ]);
        DB::transaction(function () use ($request){

            if (!Schema::hasTable($request->get("name"))) {
                Schema::create($request->get("name"), function (Blueprint $table) use ($request) {
                    $table->id();
                    foreach ($request->get("type") as $i => $type){
                        $field = $request->get("field")[$i];
                        $table->string($field);
                    }
                    $table->integer("parent_id");
                    $table->foreignId("io_type_id")->constrained();
                    $table->softDeletes();
                    $table->timestamps();
                });
            }
            $ioType = new Io_type();
            $ioType->name = $request->get("name");
            $ioType->table = $request->get("name");
            $ioType->save();


        });



        return redirect(route("types.index"));
    }


    public function show($table)
    {
        $tablename = Io_type::where("table","$table")->first();
        dd(Io_type::getConnected($table), $tablename->name);
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
