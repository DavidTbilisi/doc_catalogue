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
            DB::table($request->get("table"))
                ->insert($request->all()->except());
            DB::commit();
        }
        catch (\Exception $exception) {
            dd($request->all());
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
