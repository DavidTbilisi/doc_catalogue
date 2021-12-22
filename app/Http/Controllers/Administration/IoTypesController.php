<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Io_type;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Log;

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
        $tableColumns = Io_type::getColNames($table);

        $toRemove = array_diff($tableColumns, $request->get('cols')); // Get Deleted Columns

        foreach ($request->get('cols') as $key => $col) {
            $aCol = explode(",", $col);

            if (Schema::hasTable($table)) {
                if (count($aCol) > 1 && Schema::hasColumn($table, $aCol[0])) {
                    Io_type::rnCol($table, $aCol); // $aCol = [oldName, newName]
                } else if (! Schema::hasColumn($table, $col)){
                    Io_type::addCol($table, $col);
                }
            }
        }

        foreach ($toRemove as $key => $col) {
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
