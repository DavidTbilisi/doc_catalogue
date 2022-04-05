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

        $table = $request->get("table");
        $fields = $request->except(['table','_token']);

        $request->session()->put('search_table', $table);
        $request->session()->put('search_fields', $fields);


        $builder = DB::table($table);
        $builder->select([
            "*",
            "io.id as io_id",
            "io_types.name as io_types_name",
            "reference",
        ]);

        foreach ($fields as $name => $value):
            if ($value) {
                $builder->where($name, "like","%{$value}%");
            }
        endforeach;

        $builder->join('io_types', 'io_types.id', '=', $table.'.io_type_id');

        $builder->join('io',  function($join) use ($table){
            $join->on('io.io_type_id',"=",$table.'.io_type_id');
            $join->on('io.data_id',"=",$table.'.id');
        });


        $results = $builder->get();
//        return dd($results);
        return view("admin/search/results", [
            "results"=> $results
        ]);
    }
}
