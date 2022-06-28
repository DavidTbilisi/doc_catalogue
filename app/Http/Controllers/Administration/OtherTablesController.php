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
        $fields = array_keys($translation);

        $data = DB::table($table)->select($fields)->where("id",$id)->first();
        $data_types = DB::select("show columns from {$table}");

        $data_with_types_and_translation = [];
        foreach($data_types as $index => $field):
            if(in_array( $field->Field, $fields)) {

                $type_for_input = "text";
                if (str_contains($field->Type,"int")) {
                    $type_for_input = "number";
                } elseif (str_contains($field->Type,"date")) {
                    $type_for_input = "date";
                }


                $data_with_types_and_translation[] = [
                    "type" => $type_for_input,
                    "row_type" => $field->Type,
                    "field" => $field->Field,
                    "translation" => $translation[$field->Field],
                    "data"=> $data->{$field->Field}
                    ];
            }
        endforeach;

//        dd($data_with_types_and_translation);

        return view("admin.tables.show", [
            "data"=>$data_with_types_and_translation,
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
