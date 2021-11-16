<?php

namespace App\Http\Controllers\Administration;

use App\Models\Io;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IoController extends Controller
{

    public function index()
    {
        $ioList = Io::with("type")->get();
        return view("admin.io.io_list", ["iolist" => $ioList]);
    }


    public function create()
    {
        return view("admin.io.io_add");
    }


    public function store(Request $request)
    {
        dd($request->all());
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
