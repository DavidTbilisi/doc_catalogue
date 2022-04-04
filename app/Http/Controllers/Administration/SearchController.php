<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Io_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index()
    {

        $types = Io_type::all();

        return view("admin/search/index", [
            'types' => $types
        ]);
    }
    public function search(Request $request)
    {

        $table = $request->table;
        $builder = DB::table($table);


        return $request->all();



    }
}
