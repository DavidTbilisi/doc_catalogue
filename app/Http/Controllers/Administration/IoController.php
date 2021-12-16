<?php

namespace App\Http\Controllers\Administration;

use App\Models\Io;
use App\Models\Io_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IoController extends Controller
{

    private function buildReference($id, $request) {

        
        $reff = "GE";

        $reff .= $request->get("prefix") != NULL? "_{$request->get("prefix")}" : "";

        $reff .= $request->get("identifier") != NULL? "-{$request->get("identifier")}" : "";

        $reff .= $request->get('suffix') != NULL? "-{$request->get('suffix')}" : "";

        $ioParent = Io::find($id)->parent;
        
        $str = "";
        while ($ioParent) {
            $str .= $ioParent->prefix != null?      "_" . $ioParent->prefix: "";
            $str .= $ioParent->identifier != null?  "-" .$ioParent->identifier: "";
            $str .= $ioParent->suffix != null?      "-" . $ioParent->suffix: "";
            $ioParent = Io::find($ioParent->id)->parent;
        }

        $reff .= $str;

        return $reff;
    }

    public function index()
    {
        $ioList = Io::with("type")
            ->with("children")
            ->where("level", 1)
            ->get();


        return view("admin.io.io_list", [
            "iolist" => $ioList
        ]);
    }


    public function create()
    {
        $types = Io_type::all();
        return view("admin.io.io_add", [
            'types'=>$types
        ]);
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            if ($request->has("table")) {

                $toInsert = $request->except(["_token", 'table']);
                $table = $request->get("table");

                $io_type_id = DB::table("io_types")
                                    ->where("table", $table)
                                    ->first()
                                    ->id;

                $toInsert["io_type_id"] = $io_type_id;


                DB::table($table)->insert($toInsert);
                $last_id = DB::table($table)
                                    ->orderByDesc('id')
                                    ->first()
                                    ->id;

                DB::commit();

                return response()->json([
                    'message' => "The table \"{$request->table}\" was updated",
                    "inserted_id" => $last_id,
                    "io_type_id" => $io_type_id
                ]);

            } else {

                $io = $request->except(["_token"]);

                $io['reference'] = $this->buildReference($id, $request);

                $result = Io::create($io);

                DB::commit();

                if ($result){
                    return response()->json([
                        'message' => "row added successfully ",
                    ]);
                };
            }



        }
        catch (\Exception $exception) {
            DB::rollback();
            dd($exception);
        }
    }


    public function show($id)
    {
        $io = IO::with("type")
            ->with('parent')
            ->where('id',$id)
            ->first();

        return $io;
    }


    public function edit($id)
    {
        $io = IO::with("type")
            ->with('parent')
            ->where('id',$id)
            ->first();

        $types = Io_type::all();
        return view('admin.io.io_edit', [
            'types'=>$types, 
            'io'=>$io
        ]);
    }


    public function update(Request $request, $id)
    {
        $io = Io::findOrFail($id);
        $post = $request->except(["_token"]);


        $io->prefix = $post['prefix'];
        $io->identifier = $post['identifier'];
        $io->suffix = $post['suffix'];
        $io->io_type_id = $post['io_type_id'];
        $io->reference = $this->buildReference($id, $request);

        $io->save();

        return redirect(route("io.index"));
    }


    public function destroy($id)
    {

    }

    public function test($id)
    {
        $child = Io::find($id)->parent;
        $str = "";
        while ($child) {
            $str .= "_" .substr($child->reference, 3);
            $child = Io::find($child->id)->parent;
        }
        // $str = substr($str, 1);
        print($str);
    }
}
