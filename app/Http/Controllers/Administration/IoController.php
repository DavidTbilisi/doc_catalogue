<?php

namespace App\Http\Controllers\Administration;

use App\Models\Io;
use App\Models\Io_type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as Code;

class IoController extends Controller
{

    private function detectLevel($reference){
        $referenceArray = explode("_", $reference);
        return count($referenceArray)-1;
    }

    private function referenceChecker($prefix, $identifier, $suffix)
    {
        if ($prefix && $identifier && $suffix) {
            // "GE_p-1-s"
            return"_{$prefix}-{$identifier}-{$suffix}";

        } else if ($prefix == false && $identifier && $suffix) {
            // "GE_1-s"
            return "_{$identifier}-{$suffix}";

        } else if ($prefix && $identifier && $suffix == false) {

            // "GE_p-1"
            return "_{$prefix}-{$identifier}";

        } else if ($prefix == false && $identifier && $suffix == false) {

            // "GE_1"
            return "_{$identifier}";
        }
    }

    private function getRefRequest($request) {
        // Get reference from Current io

        $prefix = $request->get("prefix") != NULL? $request->get("prefix") : false;
        $identifier = $request->get("identifier") != NULL? $request->get("identifier") : false;
        $suffix = $request->get("suffix") != NULL? $request->get("suffix") : false;

        return $this->referenceChecker($prefix, $identifier, $suffix);
    }

    private function getRefParent($ioParent) {
        // Get reference from Current io
        $prefix  = $ioParent->prefix != null?  $ioParent->prefix: false;
        $identifier = $ioParent->identifier != null? $ioParent->identifier: false;
        $suffix = $ioParent->suffix != null?  $ioParent->suffix: false;

        return $this->referenceChecker($prefix, $identifier, $suffix);
    }

    private function buildReference($io_id, $request) {

        $io = Io::find($io_id);

        $io_is_parent = $request->get("io_parent_id") ?? false;
        $ioParent = $io ? $io->parent : false; // Returns parent ID
        $method = Str::endsWith($request->path(), "add") ? 'create' :  'update';


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
                $io["level"] = $this->detectLevel($io['reference']);

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

    private function getChildren($io){
        static $visited = [];
        static $informationObjects = [];

        if (!in_array($io->id, $visited )) {
            $visited[] = $io->id;
            $informationObjects[] = $io;
            $ios = Io::where("parent_id", $io->id)->get();
            foreach($ios as $io):
                $this->getChildren($io);
            endforeach;
        }
        return collect($informationObjects);
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






        // ვიღებთ პირველ მშობელს, ხის საწყის წერტილს.
        $io_gp = $io_item;
        while($io_gp->parent != null) {
            $io_gp = $io_gp->parent;
        }

        $ios = $this->getChildren($io_gp);
        $arr = array_values( $ios->toArray() );
        $jstreeData = [];
        foreach($arr as $key => $val) {
            // BUILDING JSON FOR JSTREE
            // Documentation for jstree json params
            // https://www.jstree.com/docs/json/
            $jstreeData[$key]["id"] = (string)$arr[$key]['id'];
            $jstreeData[$key]["a_attr"] = route("io.show", ["id" => $arr[$key]['id'] ]);
            $jstreeData[$key]["text"] = $arr[$key]['reference'];
            $jstreeData[$key]["parent"] = $arr[$key]['parent_id'] == null ? "#" : (string)$arr[$key]['parent_id'];
            $jstreeData[$key]["state"]["selected"] =  $arr[$key]['id'] ==  $id;
        }

        return view("admin.io.io_view", [
            "io"=> $io_item,
            "data" => $data,
            "translation" => $translation,
            "table" => $table,
            "children" => collect($jstreeData),
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
        $io->level = $this->detectLevel($io->reference);

        $isSaved = $io->save();

        Log::channel("app")->info("Io Update: ", ['Is Io Saved'=> $isSaved]);
        if ($isSaved) {
            return redirect(route("io.index"));
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $io = Io::findOrFail($id);
            $type = Io_type::findOrFail($io->io_type_id);

            $status = DB::table($type->table)->delete($io->data_id);
            Log::channel("app")->info("'{$type->table}'-{$io->data_id} deletion status", [$status]);



            foreach ($this->getChildren($io) as $index => $infoObject){
                $status = $infoObject->delete();
                Log::channel("app")->info("'{$io->id}' Deletion status", [$status]);
            };


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
