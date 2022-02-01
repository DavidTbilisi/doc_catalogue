<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Io_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtherTablesController extends Controller
{

    public function index()
    {

    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {

    }


    public function show($table, int $id)
    {


    }


    public function edit(string $table, int $id)
    {
        $data = DB::table($table)->select("io_type_id")->where("id",$id)->first();
        $type = Io_type::with('translation')->where("id", $data->io_type_id)->first();
        $translation = $type->translation;
        $translation = json_decode($translation->fields, true);
        $data = DB::table($table)->select(array_keys($translation))->where("id",$id)->first();

        return view("admin.tables.show", [
            "data"=>$data,
            "translation"=>$translation,
            "type"=>$type,
            'id'=>$id
        ]);
    }


    public function update(Request $request, $id)
    {

        $status = DB::table($request->get("table"))->where("id", $id)->update($request->except(["_token", 'table']));
        if ($status) return redirect()->back()->with(["message"=>"მონაცემი წარმატებით განახლდა", "type"=>"success"]);
        return redirect()->back()->with(["message"=> "მონაცემი არ განახლებულა", "type"=>"danger"]);
    }


    public function destroy($id)
    {
        //
    }
}
