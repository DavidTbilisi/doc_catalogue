<?php

namespace App\Http\Controllers\Administration;

use App\Facades\Perms;
use App\Http\Controllers\Controller;
use App\Models\Io;
use App\Models\Io_type;
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
//         boilerplate to get Fond


//        $io_item =  IO::with("type")
//            ->with('parent')
//            ->with('children')
//            ->with('type')
//            ->with('documents')
//            ->where('id',$id)
//            ->first();
//
//        $trTable = Io_type::with('translation')->where("id", $io_item->io_type_id)->first();
//        $translation = $trTable->translation;
//        $translation = json_decode($translation->fields, true);
//
//        $table = $io_item->type->table;
//        $data = DB::table($io_item->type->table)
//            ->select()
//            ->where("id", $io_item->data_id)
//            ->first();

//        $fond =
        $perms = Perms::fondPerms($id);


        return view('admin.io.io_permissions', ["data"=>$perms]);

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
