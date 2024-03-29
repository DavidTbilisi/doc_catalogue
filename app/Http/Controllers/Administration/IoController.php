<?php

namespace App\Http\Controllers\Administration;

use App\Facades\Perms;
use App\Models\Document;
use App\Models\Group;
use App\Models\Io;
use App\Models\Io_type;
use App\Models\IoGroupsPermissions;
use FilesystemIterator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use JildertMiedema\LaravelPlupload\Facades\Plupload;
use Symfony\Component\HttpFoundation\Response as Code;
use Jeremytubbs\LaravelDeepzoom\Commands\MakeTiles;


class IoController extends Controller
{

    use DispatchesJobs;

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
        return null;
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
            ->paginate();
        $typesCount = Io_type::all()->count();

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
            "ioTypeCount" => $typesCount
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


                $groups = Group::all();
                foreach ($groups as $group):
                    $igp = new IoGroupsPermissions;
                    $igp->io_id = $result->id;
                    $igp->groups_id = $group->id;
                    $igp->permission = 127;
                    $igp->save();
                endforeach;


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
        return response()->json(['message' => "Bad Request",], Code::HTTP_BAD_REQUEST);
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

        $hasPerms = Perms::hasPermsIo($id, ["viewObject"]); // ვამოწმებ საინფორმ. ობიექტის უფლებებს.
        if (!$hasPerms) {
            return redirect(route("dashboard"))->withErrors(["msg"=>"წვდომა შეზღუდულია"]);
        }

        $io_item =  IO::with("type")
            ->with('parent')
            ->with('children')
            ->with('type')
            ->with('documents')
            ->where('id',$id)
            ->first();

        // თარგმის ცხრილის შემოწმება
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


/*
 * COUNT FILES IN FOLDER
 * https://stackoverflow.com/questions/12801370/count-how-many-files-in-directory-php
 * $fi = new FilesystemIterator(__DIR__);
 * printf("There were %d Files", iterator_count($fi));
 *
 */
        $filepath = str_replace("GE_","public/files/", $io_item->reference);
        $filepath = str_replace("_","/",$filepath) . "/";

        $prefix = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $absolute_path = $prefix.$filepath;
        $file_pool_count = -1;

        if(!file_exists($absolute_path)) {
          mkdir($absolute_path, 0700, true);
        }

        $fi = new FilesystemIterator($absolute_path);
        $file_pool_count = iterator_count($fi);

        $el_path0 = substr($filepath, strlen("public/files/"));
        $el_path = $this->el_start_dir($el_path0);

        return view("admin.io.io_view", [
            "io"=> $io_item,
            "data" => $data,
            "translation" => $translation,
            "table" => $table,
            "children" => collect($jstreeData),
            "el_path"=> $el_path,
            "pool_count" => $file_pool_count
        ]);
    }

    private function el_start_dir($path){
        $volumeId = 'l1_';
        $hash = $volumeId . rtrim(strtr(base64_encode($path), '+/=', '-_.'), '.');
        return $hash;
    }


    public function edit($id)
    {
        $io = IO::with("type")
            ->with('parent')
            ->with('documents')
            ->where('id',$id)
            ->firstOrFail();


        $types = Io_type::all();
        $path = substr($io->reference, 3);
        $path = str_replace("_","/", $path);

        return view('admin.io.io_edit', [
            'types'=>$types,
            'io'=>$io,
            'startPath' => $this->el_start_dir("files/".$path)
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


        if ($request->hasFile ("file") ):
            Plupload::receive('file', function ($file) use ($io){

                # Save Files
                list($path, $filename) = $this->build_filename($file, $io);
                $db_path = $this->save_images($path, $filename, $file);
                $this->save_images_path_to_db($filename, $db_path, $io, $file);
            });
        endif;

        $io->save();
        return redirect(route('io.edit',["id"=>$io->id]));

    }

    private function last_image_index($io, $index=0){
        $doc = Document::where("io_id", $io->id)->orderby('created_at', 'desc')->first();
        if (!$doc) {
            $doc = new Document();
        } else {
            $name = explode('.', $doc->filename)[0];
            $index += substr($name, -1);
        }
        return $index;
    }

    private function build_filename($file, $io){
        $index = $this->last_image_index($io);
        $index++;


        $file_ext = $file->getClientOriginalExtension();
        $path = str_replace("_", "/", substr($io->reference, 3));
        $filename = "{$io->reference}_{$index}.{$file_ext}";
        return [$path, $filename];
    }

    private function create_thumb($db_path) {
        try{
            $thumb_path = base_path("public/storage/thumbs/".$db_path);
            $thumb_path_without_filename = pathinfo($thumb_path, PATHINFO_DIRNAME);

            if (!file_exists($thumb_path_without_filename)) {
                if(!mkdir($thumb_path_without_filename, 01777, true)){
                    Log::channel('app')->info("{$thumb_path_without_filename} can't be created");
                };
            }

            $img = Image::make(app_path("../storage/app/public/".$db_path))->resize(300, 185, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save($thumb_path);

        } catch (\Exception $e) {
            Log::channel("app")->info("thumb creation", [
                "message"=>$e->getMessage(),
                "dir"=>$thumb_path_without_filename,
            ]);
        }
    }


    private function tile_create($image, $filename = null, $folder = null){
                $folder = pathinfo(base_path("public/storage/tiles/".$folder), PATHINFO_FILENAME);

                try {

                    $img = storage_path("app/public/".$image);
                    Log::channel("app")->info("image path to make tiles", ["image"=>$img]);

                    $command = new MakeTiles($img, $folder, $folder); // there should be a double folder name, that's not a mistake

                    $this->dispatch($command);

                } catch (\Exception $e) {
                    Log::channel("app")->info("Can't create tiles", ["msg"=>$e->getMessage()]);
                }
    }


    private function save_images ($path, $filename, $file){

        $path = $file->storeAs("public/documents/" . $path, $filename);
        $db_path = substr($path, strpos($path, "/") + 1);
        $this->create_thumb($db_path);
        $this->tile_create($db_path, $filename, $db_path);

        Log::channel("app")->info("File was added to", ["path" => $path]);
        return $db_path;
    }

    private function save_images_path_to_db($filename, $db_path, $io, $file){
        $doc = Document::where("io_id", $io->id)->orderby('created_at', 'desc')->first();

        $index = 0;

        if (!$doc) {
            $doc = new Document();
        }  else {
            $name = explode('.', $doc->filename)[0];
            $index += substr($name, -1);
            $doc = new Document();
        }

        $alreadyInDb = Document::where("filename", $filename)->get()->count();


        if(!$alreadyInDb){
            try{
            $img = Image::make("public/".$db_path);
            } catch (\Exception $e) {
                Log::channel("app")->info($e->getMessage());
            }
            $doc->io_id = $io->id;
            $doc->filename = $filename;
            $doc->filepath = $db_path;
            $doc->size = Storage::size("public/".$db_path);
            $doc->checksum = md5(Storage::get("public/".$db_path));
            $doc->mimetype = $file->getMimeType();
            $doc->save();
        }
        return $index;
    }



    public function destroy($id)
    {
        echo $id;
        DB::beginTransaction();
        try{


            $io = Io::findOrFail($id);
            $type = Io_type::findOrFail($io->io_type_id);
            $type_table_delete_status = DB::table($type->table)->where("id",$io->data_id)->delete();

            Log::channel("app")->info("'{$type->table}'-{$io->data_id} deletion status", [$type_table_delete_status]);

            $io_children = $this->getChildren($io);

            if (count($io_children) > 0){
                foreach ($io_children as $infoObject){

                    $docs = Document::where("io_id",$infoObject->id);

                    foreach($docs->get() as $d) :
                        Storage::delete("public/".$d->filepath);
                    endforeach;

                    IoGroupsPermissions::where("io_id", $io->id)->delete();

                    $docs->delete();
                    $io_delete_status = $infoObject->delete();
                    Log::channel("app")->info("'{$io->id}' Deletion status", [$io_delete_status]);
                };
            }


            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::channel("app")->info("Deletion status", [$exception->getMessage()]);

            return redirect(route('io.index'))->with([
                "type"=> "danger",
                "message"=> "მონაცემის წაშლა ვერ მოხერხდა, რადგანაც მოცემული მონაცემი არ არის ცარიელი"
            ]);
        }


        return redirect(route("io.index"))->with([
            "type"=> "success",
            "message"=> "მონაცები წარმატებით წაიშალა"
        ]);
    }




}
