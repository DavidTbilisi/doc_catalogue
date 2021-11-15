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
        return view("admin.io.index", ["iolist" => $ioList]);
    }


    public function create()
    {

    }


    public function store(Request $request)
    {

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
