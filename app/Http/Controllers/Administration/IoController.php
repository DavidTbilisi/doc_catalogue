<?php

namespace App\Http\Controllers\Administration;

use App\Models\Io;
use App\Models\Io_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IoController extends Controller
{

    public function index()
    {
        $ioList = Io::with("type")
            ->with("children")
            ->where("level", 1)
            ->get();


        return view("admin.io.io_list", ["iolist" => $ioList]);
    }


    public function create()
    {
        $types = Io_type::all();
        return view("admin.io.io_add", ['types'=>$types]);
    }


    public function store(Request $request)
    {

        DB::beginTransaction();
        try{
            if ($request->has("table")) {

                $toInsert = $request->except(["_token", 'table']);
                $table = $request->get("table");

                $io_type_id = DB::table("io_types")->where("table", $table)->first()->id;
                $toInsert["io_type_id"] = $io_type_id;

                DB::table($table)->insert($toInsert);
                $last_id = DB::table($table)->orderByDesc('id')->first()->id;


                return response()->json([
                    'message' => "The table \"{$request->table}\" was updated",
                    "inserted_id" => $last_id,
                    "io_type_id" => $io_type_id
                ]);

            } else {

                $result = Io::create($request->except(["_token"]));

                DB::commit();

                if ($result){
                    return response()->json([
                        'message' => "row added successfully ",
                    ]);
                };
            }



        }
        catch (\Exception $exception) {
            dd($exception);
        }
    }


    public function show($id)
    {

    }


    public function edit($id)
    {

    }


    public function update(Request $request, $id)
    {

    }


    public function destroy($id)
    {

    }
}
