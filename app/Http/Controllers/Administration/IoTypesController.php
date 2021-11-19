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
        return view("admin.iotypes.type_list", ['types'=>$types]);
    }


    public function create()
    {
        $types = Io_type::all();
        return view("admin.iotypes.add_type", ["types"=>$types]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|max:255',
            'tablename'     => 'required|alpha|max:20',
            'field'         => 'required|max:255',
            'type'          => 'required|max:20',
        ]);

        DB::beginTransaction();
        try {

            if (!Schema::hasTable($request->get("tablename"))) {
                Schema::create($request->get("tablename"), function (Blueprint $table) use ($request) {
                    $table->id();
                    foreach ($request->get("type") as $i => $type){
                        $field = $request->get("field")[$i];
                        $table->string($field);
                    }

//                    TODO: parent_id -ს ვერ ხვდება რისია.
                    /*
                     * ან თეიბლის სახელი უნდა წამოვიღო და იმის tablename_id-ი მივაწერო
                     * ან დავტოვო უბრალოდ ინტ-ად და ვიმკითხავო რომელია მისი მშობელი.
                     * */
                    $table->foreignId("parent_id")->constrained("fonds");
                    $table->foreignId("io_type_id")->constrained();
                    $table->softDeletes();
                    $table->timestamps();
                });
            }
            $ioType = new Io_type();
            $ioType->name = $request->get("name");
            $ioType->table = $request->get("tablename");
            $ioType->parent_id = $request->get("parent_id");
            $ioType->save();

            DB::commit();
            return redirect(route('types.index'));

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }
    }


    public function show($table)
    {
        $tablename = Io_type::where("table","$table")->first();
        $columns = Io_type::getColumns($table);

        return view("admin.iotypes.type", ["tablename"=>$tablename, "columns"=>$columns]);
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            Io_type::find($id)->delete();
            Schema::dropIfExists(request()->get("table"));

            DB::commit();
            return redirect(route('types.index'));
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
        }

    }


}
