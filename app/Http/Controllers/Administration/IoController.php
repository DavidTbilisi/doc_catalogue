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


    private function getRefRequest($request) {
        // Get reference from Current io
        $reqReference  = '';
        $reqReference .= $request->get("prefix") != NULL? "_{$request->get("prefix")}" : "";
        $reqReference .= $request->get("identifier") != NULL? "-{$request->get("identifier")}" : "";
        $reqReference .= $request->get('suffix') != NULL? "-{$request->get('suffix')}" : "";
        return $reqReference;
    }



    private function getRefParent($ioParent) {
        // Get reference from Current io
        $parentReference  = '';
        $parentReference .= $ioParent->prefix != null?      "_" . $ioParent->prefix: "";
        $parentReference .= $ioParent->identifier != null?  "-" .$ioParent->identifier: "";
        $parentReference .= $ioParent->suffix != null?      "-" . $ioParent->suffix: "";
        return $parentReference;
    }

    private function buildReference($io_type_id, $request) {
        
        $ioType = Io::find($io_type_id);

        $ioParent = $ioType ? $ioType->parent : false;

        Log::channel("app")->info("Reference Builder: ", ["ioType"=>$ioType, "has_parent"=> $ioParent]);

        $str = '';
        $reff = "GE";

        // Get reference from Current io 
        $currentIoReference = $this->getRefRequest($request);
        $reff .= $currentIoReference;
        $refsArray = [$currentIoReference];


        while ($ioParent) {
            $parentRef = $this->getRefParent($ioParent);
            $str .= $parentRef;
            $ioParent = Io::find($ioParent->id)->parent;

            array_push($refsArray, $parentRef);
        }

        $reff .= $str;

        $reversedOrderOfRefs = array_reverse($refsArray);
        $parentReferences = "GE".implode("", $reversedOrderOfRefs); // joining reversed references to string
        Log::channel('app')->info("Reverse Refs", [
            "arr_of_refs" => $refsArray,
            "reversed_arr_of_refs" => $reversedOrderOfRefs,
            "reversed_str_of_refs" => $parentReferences,
        ]);

        return $parentReferences;
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
        $types = Io_type::with('translation')->get();
        return view("admin.io.io_add", [
            'types'=>$types
        ]);
    }


    private function type_create($request){
                
            $toInsert = $request->except(["_token", 'table']);
            $table = $request->get("table");
            $io_type_id = Io_type::getTypeId($table);

            Log::channel('app')->info(
                "creating data in table {$table}"
            );

            $toInsert["io_type_id"] = $io_type_id;            
            

            DB::table($table)->insert($toInsert);


            $last_id = DB::table($table)
                                ->orderByDesc('id')
                                ->first()
                                ->id;

            return [
                'message' => "The table \"{$request->table}\" was updated",
                "inserted_id" => $last_id,
                "io_type_id" => $io_type_id
            ];
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            if ($request->has("table")) {
                /*
                 * If Table -> Insert into that table | ჯერ ვინახავ კონკრეტული თეიბლის მონაცემს
                 * Else -> insert into Io | შემდეგ, უკვე შენახულის შესახებ ინფორმაციას ვინახავ io თეიბლში
                 *
                 * Ajax send request with "table" (name of table)
                 * and right after that it sands another request with 
                 * "inserted_id" and "io_type_id" for Io Table
                */

                $created_type = $this->type_create($request);
                
                DB::commit();
                
                return response()->json($created_type, Code::HTTP_CREATED);

            } else {

                // INSERT INTO IO TABLE
/*
                params: 
                - data_id: int
                - io_type_id: int
                - suffix: str
                - identifier: int
                - prefix: str
                - reference: str
                - level: int
                - parent_id: int
*/

                $io = $request->except(["_token"]);
                
                $io['reference'] = $this->buildReference($io['io_parent_id'], $request);

                if ($request->has("io_parent_id")) {

                    Log::channel('app')->info("Io Parent Test",[
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
