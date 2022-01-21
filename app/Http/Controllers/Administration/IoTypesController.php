<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Io_type;
use App\Models\Io_types_translation;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use stdClass;
use Symfony\Component\HttpFoundation\Response as Code;


class IoTypesController extends Controller
{

    public function index()
    {
        $types = Io_type::all();
        return view("admin.iotypes.type_list", [
            'types'=>$types
        ]);
    }


    public function create()
    {
        $types = Io_type::all();
        return view("admin.iotypes.add_type", [
            "types"=>$types
        ]);
    }



    private function create_type_table($toCreateTable, $request) {

        $fields = new stdClass();

        // create Type Table 
        if (!Schema::hasTable($toCreateTable)) {

            Schema::create( $toCreateTable, 
                function (Blueprint $table) use ($request, $fields) {
                
                    $table->id();
                    // იქმნება გადმოცემული Column-ები მითითებული Type-ებით
                    foreach ($request->get("type") as $i => $type){
                        $field = $request->get("field")[$i];
                        $name = $request->get("names")[$i];
                        
                        // build return couples 
                        $fields->$field = $name;

                        $table->$type($field)->nullable();

                    }
                    $table->foreignId("io_type_id")->constrained()->nallable();
                    $table->softDeletes();
                    $table->timestamps();
            });
        } else {
            throw new \Exception('Table already exists');
        }

        return (array)$fields;
    }



    private function register_type_table_translation($io_types_table_id, $fields) {

        $io_type = Io_type::find($io_types_table_id);
        $fhuman = Io_types_translation::firstOrCreate([
            "io_type_id"=>$io_type->id
        ],[
            "fields" => json_encode($fields, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        ]);


        $fhuman->io_type_id = $io_type->id;
        $fhuman->fields = json_encode($fields, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $fhuman->save();
        
    }
        

    public function store(Request $request)
    {

        $request->validate([
            'name'          => 'required|max:50',
            'tablename'     => 'required|alpha|max:20',
            'field'         => 'required|max:100',
            'type'          => 'required|max:20',
        ]);



        DB::beginTransaction();
        try {
            $toCreateTable = $request->get("tablename");

            $fields = $this->create_type_table($toCreateTable, $request); // returns fields array

            // თუ შეიქმნა Table -ი ვქმნით ჩანაწერს მის შესახებ io_types-შიც
            $ioType = new Io_type();
            $ioType->name = $request->get("name");
            $ioType->table = $request->get("tablename");
            $status = $ioType->save();

            
            $this->register_type_table_translation($ioType->id, $fields);

            DB::commit();
            Log::channel("app")->debug("Type Table Created Successfully", [$status]);

            return redirect(route('types.index'));

        } catch (\Exception $e) {
            DB::rollback();
            Log::channel("app")->debug("Type Table Not Created ", [$e]);
            return redirect(route('types.add'))
                ->withErrors([
                    "msg"=>$e->getMessage()
                ]);
        }
    }


    public function show($table)
    {
        $tablename = Io_type::where("table","$table")->with("translation")->first();
        $columns = Io_type::getColumns($table, false);

        $translation = $tablename->translation->fields;
        $translation = json_decode($translation, true);
      
        return view("admin.iotypes.type", [
            "tablename"=>$tablename, 
            "translation"=>$translation, 
            "columns"=>$columns
        ]);
    }


    public function edit($table)
    {
        $tablename = Io_type::where("table","$table")->first();
        $columns = Io_type::getColumns($table);

        return view("admin.iotypes.type", [
            "tablename"=>$tablename, 
            "columns"=>$columns
        ]);
    }


    public function update(Request $request, $id)
    {
        // TODO: type updated 
    }

    private function prepareColumnConditions($table, $col){

        $old = null; $new = null; $tableHasColumn = false;

        $tableColumns = Io_type::getColNames($table);
        $aCol = explode(",", $col);
        $is_renameable = count($aCol) > 1;

        if($is_renameable):

            list($old, $new) = explode(",", $col);

            $tableHasColumn = in_array($old, $tableColumns);

            Log::channel("app")->info("is_renameable", [
                "Field"=> $aCol, 
                "is_renameable"=> $is_renameable,
                "exists_{$table}:{$old}"=> $tableHasColumn,
            ]);

        else:
            $tableHasColumn = in_array($col, $tableColumns);
        endif;

        return [
            $is_renameable,
            $tableHasColumn,
        ];
    }


    public function columnChange(Request $request){

        $request->validate([
            'cols'          => 'required',
            'table'         => 'required',
            'names'         => 'required',
        ]);



        $table = $request->get("table");
        $requestCols = $request->get('cols');
        $requestColTranslations = $request->get('names');

        $tableColumns = Io_type::getColNames($table);
        $technicalNames = new stdClass();
        $table_type = Io_type::where("table",$table)->first();

     
        
        if ($requestCols != null ):


            foreach ($requestCols as $col):

                // prepare table columns
                $prepared = $this->prepareColumnConditions($table, $col);

                list($is_renameable, $tableHasColumn) = $prepared;
    
                if (Schema::hasTable($table)):

                    if ( $is_renameable && $tableHasColumn ) {

                        list($old, $new) = explode(",", $col);

                        Log::channel("app")->info("renaming", [
                            "Old name"=> $old, 
                            "New name"=> $new
                        ]);
                        Io_type::rnCol($table, [$old, $new]); 

                    } else if (! $tableHasColumn && ! $is_renameable){

                        Log::channel("app")->info("Adding Field to '{$table}' table", [
                            "Field"=> $col,
                        ]);

                        Io_type::addCol($table, $col);
                    }

                endif; // table exists
            endforeach;
        endif; // if post columns exist



        $toRemove = array_diff($tableColumns, $requestCols); // Get Deleted Columns

        Log::channel("app")->debug("To Remove Fields", [$toRemove]);
        Log::channel("app")->info("All Fields of {$table} table ".__FILE__.":", [
            "TableColumns" => $tableColumns,
            "TableColumnsTranslation" => $requestColTranslations,
        ]);
        foreach ($toRemove as $col) {
            Io_type::rmCol($table, $col);
        }


        // TODO: აქ გვინდა შევადაროთ არსებული ველები ყველა მანიპულაციის მერე
        // გადმოცემულ ველებთან რომისგანაც უნდა შევადგინოთ ასოციაციური მასივი (zip)
        $type_col_names = Io_type::getColNames($table);
        if(count($type_col_names) == count($requestColTranslations)){
            foreach ($tableColumns as $index => $technicalName):
                $fieldsAndTranslations[$technicalName] = $requestColTranslations[$index]; 
            endforeach;
            $this->register_type_table_translation($table_type->id, $fieldsAndTranslations);
        }


        return redirect(route("types.show",["id"=>$table]));
    }

   

    public function destroy($id)
    {
        // https://laravel.com/docs/8.x/database#database-transactions
        // https://www.php.net/manual/en/pdo.begintransaction.php
        DB::beginTransaction();
        try {
            // ვშლი ჩანაწერს io_type tabel-დან და თავად table-ს
            $type = Io_type::findOrfail($id); // 404 თუ ვერ იპოვა ჩანაწერი
            $type->delete();

            Schema::dropIfExists(request()->get("table"));

            DB::commit(); 
            return redirect(route('types.index'));
        } catch (\Exception $e) {

            Log::channel("app")->info("Type Not Deleted", [
                "message"=>$e->getMessage(),
                "code"=>$e->getCode()
            ]);

            DB::rollback();

            if ($e->getCode() == "23000") {

                return redirect(route('types.index'))
                ->withErrors([
                    "msg"=>"ცხრილის წაშლა შეუძლებელია, სანამ მასზე მიბმულია IO"
                ]);

            } else {
                return redirect(route('types.index'));
            }
        }

    }


}
