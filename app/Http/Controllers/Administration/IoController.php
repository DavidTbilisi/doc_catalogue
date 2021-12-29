<?php

namespace App\Http\Controllers\Administration;

use App\Models\Io;
use App\Models\Io_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as Code;

class IoController extends Controller
{

    private function buildReference($io_type_id, $request) {
        
        $reff = "GE";

        $reff .= $request->get("prefix") != NULL? "_{$request->get("prefix")}" : "";

        $reff .= $request->get("identifier") != NULL? "-{$request->get("identifier")}" : "";

        $reff .= $request->get('suffix') != NULL? "-{$request->get('suffix')}" : "";


        $ioType = Io::find($io_type_id);

        $ioParent = $ioType ? $ioType->parent : false;

        Log::channel("app")->info("Reference Builder: ", ["ioType"=>$ioType, "has_parent"=> $ioParent]);

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
                
                // If Table -> Insert into that table 
                // Else -> insert into Io 

                /* Ajax send request with "table" (name of table)
                 * and right after that it sands another request with 
                 * "inserted_id" and "io_type_id" for Io Table
                */

            
                $toInsert = $request->except(["_token", 'table']);
                $table = $request->get("table");
                $io_type_id = Io_type::getTypeId($table);

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
                ], Code::HTTP_CREATED);

            } else {

                // INSERT INTO IO TABLE

                $io = $request->except(["_token"]);
                
                $io['reference'] = $this->buildReference($io['io_type_id'], $request);

                if ($request->has("io_parent_id")) {

                    Log::channel('app')
                    ->info("Io Parent Test",[
                        "has_parent"=>$request->get("io_parent_id")
                    ]);

                    $io['parent_id'] = $request->get("io_parent_id");
                }

                $result = Io::create($io);

                DB::commit();

                if ($result){
                    return response()->json([
                        'message' => "row added successfully ",
                    ], Code::HTTP_CREATED);
                };
            }
        }
        catch (\Exception $exception) {
            DB::rollback();
            return response()->json(['message' => $exception,], Code::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        $io_item =  IO::with("type")
            ->with('parent')
            ->with('type')
            ->where('id',$id)
            ->first();

        $data = DB::table($io_item->type->table)->where("id", $io_item->data_id)->get();

        return view("admin.io.io_view", [
            "io"=> $io_item,
            "data" => $data
        ]);
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
        $io = Io::findOrFail($id); // If Id not specified return code 
        
        $post = $request->except(["_token"]);


        $io->prefix = $post['prefix'];
        $io->identifier = $post['identifier'];
        $io->suffix = $post['suffix'];
        $io->io_type_id = $post['io_type_id'];
        $io->reference = $this->buildReference($id, $request);
        $isSaved = $io->save();

        Log::channel("app")->info("Io Update: ", ['Is Io Saved'=> $isSaved]);
        if ($isSaved) {
            return redirect(route("io.index"));
        } 
    }


    public function destroy($id)
    {
        // TODO: add deletion test
        $item = Io::findOrFail($id);
        $status = $item->delete();
        if($status) {
            return redirect(route("io.index"));
        }
        Log::channel("app")->info("Deletion status", [$status]);
    }


}
