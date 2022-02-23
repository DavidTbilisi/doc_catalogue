<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Io_type;
use App\Models\Io_types_translation;
use Database\Seeders\HumanreadableSeeder;
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



    public function convert_to_tech_name($str)
    {
        $convert_array = [
            "ა" => "a",
            "ბ" => "b",
            "გ" => "g",
            "დ" => "d",
            "ე" => "e",
            "ვ" => "v",
            "ზ" => "z",
            "თ" => "t",
            "ი" => "i",
            "კ" => "k",
            "ლ" => "l",
            "მ" => "m",
            "ნ" => "n",
            "ო" => "o",
            "პ" => "p",
            "ჟ" => "J",
            "რ" => "r",
            "ს" => "s",
            "ტ" => "t",
            "უ" => "u",
            "ფ" => "f",
            "ქ" => "q",
            "ღ" => "r",
            "ყ" => "y",
            "შ" => "s",
            "ჩ" => "c",
            "ც" => "c",
            "ძ" => "z",
            "წ" => "w",
            "ჭ" => "w",
            "ხ" => "x",
            "ჯ" => "j",
            "ჰ" => "h",
            "\""=> "",
        ];
        $return = "";

        $re = '/[ \?\d]/m';
        $converted = preg_replace($re, "", $str);

        foreach(mb_str_split($converted) as $char)  in_array( $char, array_keys($convert_array) ) ? $return .= $convert_array[$char]: $return .= $char;
        //
        $return = trim(filter_var ($return, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH));
        return $return;
    }


    private function create_type_table($toCreateTable, $request) {

        $fields = new stdClass();

        // create Type Table
        $toCreateTable = $this->convert_to_tech_name($toCreateTable);

        if (!Schema::hasTable($toCreateTable)) {

            Schema::create( $toCreateTable,
                function (Blueprint $table) use ($request, $fields) {

                    $table->id();
                    // იქმნება გადმოცემული Column-ები მითითებული Type-ებით
                    foreach ($request->get("type") as $i => $type){
                        $name = $request->get("names")[$i];
                        $field = $this->convert_to_tech_name($name);

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
        $fhuman = Io_types_translation::firstOrCreate(["io_type_id"=>$io_type->id],[
            "fields" => json_encode($fields, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        ]);

        $fhuman->io_type_id = $io_type->id;
        $fhuman->fields = json_encode($fields, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        return $fhuman->save();
    }


    public function store(Request $request)
    {

        $request->validate([
            'name'          => 'required|max:50',
            'names'         => 'required|max:100',
            'type'          => 'required|max:20',
        ]);


        DB::beginTransaction();
        try {
            $toCreateTable = $this->convert_to_tech_name($request->get("name"));

            $fields = $this->create_type_table($toCreateTable, $request); // Return fields array

            // თუ შეიქმნა Table -ი ვქმნით ჩანაწერს მის შესახებ io_types-შიც
            $ioType = new Io_type();
            $ioType->name = $request->get("name");
            $ioType->table = $toCreateTable;
            $status = $ioType->save();
            Log::channel("app")->debug("Type Table Created Successfully", [$status]);


            $status = $this->register_type_table_translation($ioType->id, $fields);
            Log::channel("app")->debug("Type Table Translation Registered Successfully", [$status]);

            DB::commit();

            return redirect(route('types.index'));

        } catch (\Exception $e) {
            DB::rollback();
            Log::channel("app")->debug("Type Table Not Created ", [$e]);
            return redirect(route('types.add'))
                ->withErrors([ "msg"=>$e->getMessage() ]);
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
            'table'         => 'required',
            'names'         => 'required',
        ]);


        $table = $request->get("table");
        $table_type = Io_type::where("table",$table)->first();

        $requestColsWithTranslation = $request->get('names');

        $cols = [];
        // adding translation
        foreach($requestColsWithTranslation as $index => $col)  $cols[$this->convert_to_tech_name($col)] = $requestColsWithTranslation[$index];
        $requestColsWithTranslation = $cols;
        $this->register_type_table_translation($table_type->id, $requestColsWithTranslation);


        Log::channel('app')->info("Add or Rename cols",["cols" => $requestColsWithTranslation] );


        $dbCol = Io_type::getColNames($table);




        if ($requestColsWithTranslation != null ): // if there is a request

            foreach ($requestColsWithTranslation as $col => $translation):
                Log::channel("app")->info("Cols and Translations", [
                    "col" => $col,
                    "translation" => $translation,
                ]);


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



        $toRemove = array_diff($dbCol, array_keys($requestColsWithTranslation)); // Get Deleted Columns

        Log::channel("app")->debug("To Remove Fields", [
            'to_remove'=>$toRemove,
            'with request'=> $requestColsWithTranslation,
            'from database'=> $dbCol,
        ]);


        Log::channel("app")->info("All Fields of {$table} table ".__FILE__.":", [
            "TableColumns" => $dbCol,
            "TableColumnsTranslation" => $requestColsWithTranslation,
        ]);


        foreach ($toRemove as $col) {
            Io_type::rmCol($table, $col);
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
            $translation = Io_types_translation::where('io_type_id',$id)->firstOrFail();

            $translation->delete();
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
