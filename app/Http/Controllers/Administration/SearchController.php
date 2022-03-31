<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Io_type;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {

        $types = Io_type::all();


        return view("admin/search/index", [
            'types' => $types
        ]);
    }
}
