<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Io_type;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

            if (!Schema::hasTable($toCreateTable)) {

                Schema::create( $toCreateTable, 
                    function (Blueprint $table) use ($request) {
                    
                        $table->id();
                        // იქმნება გადმოცემული Column-ები მითითებული Type-ებით
                        foreach ($request->get("type") as $i => $type){
                            $field = $request->get("field")[$i];
                            $table->$type($field)->nullable();
                        }
                        $table->foreignId("io_type_id")->constrained();
                        $table->softDeletes();
                        $table->timestamps();
                });
            }

            // თუ შეიქმნა Table -ი ვქმნით ჩანაწერს მის შესახებ io_types-შიც
            $ioType = new Io_type();
            $ioType->name = $request->get("name");
            $ioType->table = $request->get("tablename");
            $ioType->save();

            // DB::commit();
            return redirect(route('types.index'));

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }


    public function show($table)
    {
        $tablename = Io_type::where("table","$table")->first();
        $columns = Io_type::getColumns($table, false);

        return view("admin.iotypes.type", [
            "tablename"=>$tablename, 
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
        //
    }

    public function columnChange(Request $request){




        $table = $request->get("table");

        $columnsDb = Io_type::getColumns($table, false); // False -> not Json / Array of Objects;

        foreach ($columnsDb as $key => $value) {
            $tableColumns[] = $value->Field;
        }
        $toRemove = array_diff($tableColumns, $request->get('cols'));
        dump($toRemove);

        foreach ($request->get('cols') as $key => $col) {
            $aCol = explode(",", $col);
            dump($aCol);

            if (Schema::hasTable($table)) {

                if (count($aCol) > 1 && Schema::hasColumn($table, $aCol[0])) {
                    // RENAME
                    Schema::table($table, function (Blueprint $table) use ($aCol){
                        dump( $table->renameColumn($aCol[0], $aCol[1]) );
                    });


                } else if (! Schema::hasColumn($table, $col)){
                    // ADD
                    Schema::table($table, function (Blueprint $table) use ($col){
                        $table->string($col);
                    });

                }

            }

            foreach ($toRemove as $key => $col) {
                if (Schema::hasColumn($table, $col)) {
                    // REMOVE
                    Schema::table($table, function($table) use ($col){
                        $table->dropColumn($col);
                      });
                }
            }

        }


        // return redirect(route("types.show",["id"=>$table]));
    }

    // TODO: remove this function 
    public function renameColumn(Request $request){
        $table = $request->get("table");
        $col = $request->get("col");

        if (Schema::hasTable($table)) {
            if (Schema::hasColumn($table, $col)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->renameColumn('from', 'to');
                });
            } 
        }
    }

    // TODO: remove this function 
    public function addColumn(Request $request){

        $table = $request->get("table");
        $col = $request->get("col");

        if (Schema::hasTable($table)) {
            if (Schema::hasColumn($table, $col)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->renameColumn('from', 'to');
                });
            } 
        }
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

            // DB::commit(); 
            return redirect(route('types.index'));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('types.index'));

        }

    }


}
