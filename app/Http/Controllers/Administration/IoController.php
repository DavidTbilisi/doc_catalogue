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

    private function buildReference($io_id, $request) {

        $io = Io::find($io_id);


        $ioParent = ($io && property_exists($io, 'identifier')) ? $io->parent : false; // Returns parent ID

        $method = $ioParent == null && $io_id == null ? "create" : 'update';

        Log::channel("app")->info("Reference Builder: ", [
            "io_ref" => $io,
            "has_parent" => $ioParent
        ]);

        // Get reference from Current io
        $currentIoReference = $this->getRefRequest($request);

        // init values
        $refsArray = [$currentIoReference];
        // dd($this->getRefParent($io));
        if ($method === 'create' && $io) {
            $ioParent = $io->parent;
            $refsArray[] = $this->getRefParent($io);
        }

        // უნილაკურობის გარეშე ედიტორების დროს მეორდება ელემენტები
        $refsArray = array_unique($refsArray); // generalize for editing and inserting

        Log::channel("app")->info("Ref Array:", ["ref " => $refsArray]);

        while ($ioParent) {
            $parentRef = $this->getRefParent($ioParent); // Get reference by parent
            $refsArray[] = $parentRef;
            $ioParent = Io::find($ioParent->id)->parent;
        }


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
            ->where("parent_id", null)
            ->get();


        $identifiers = [];
        foreach($ioList as $io):
            $identifier = '';
            $identifier .= $io->prefix != ""? $io->prefix:"";
            $identifier .= $io->prefix != ""? "-". $io->identifier : $io->identifier;
            $identifier .= $io->suffix != ""? "-". $io->suffix : "";
            array_push($identifiers, $identifier);
        endforeach;

        return view("admin.io.io_list", [
            "iolist" => $ioList,
            "identifiers" => $identifiers,
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

                if ($request->has("io_parent_id")) {

                    Log::channel('app')->info("Io Parent in request",[
                        "has_parent"=>$request->get("io_parent_id")
                    ]);

                    $io['parent_id'] = $request->get("io_parent_id");
                }

                $io['reference'] = $this->buildReference($io['parent_id'], $request);

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
            return response()->json(['message' => $exception->getMessage(),], Code::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        $io_item =  IO::with("type")
            ->with('parent')
            ->with('children')
            ->with('type')
            ->where('id',$id)
            ->first();

        $trTable = Io_type::with('translation')->where("id", $io_item->io_type_id)->first();
        $translation = $trTable->translation;
        $translation = json_decode($translation->fields, true);

        $table = $io_item->type->table;
        $data = DB::table($io_item->type->table)
            ->select()
            ->where("id", $io_item->data_id)
            ->first();

        $children = Io::listChildren($io_item);

        return view("admin.io.io_view", [
            "io"=> $io_item,
            "data" => $data,
            "translation" => $translation,
            "table" => $table,
            "children" => $children,
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

        DB::beginTransaction();
        try{
            $io = Io::findOrFail($id);
            $type = Io_type::findOrFail($io->io_type_id);

            $status = DB::table($type->table)->delete($io->data_id);
            Log::channel("app")->info("'{$type->table}'-{$io->data_id} deletion status", [$status]);

            $status = $io->delete();
            Log::channel("app")->info("'{$io->id}' Deletion status", [$status]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect(route('io.index'))->withErrors("message", $exception->getMessage());
        }

        if($status) {
            return redirect(route("io.index"));
        }
    }


}
