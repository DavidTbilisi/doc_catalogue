<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Io_type extends Model
{
    use HasFactory;

    public function ios()
    {
        $this->belongsTo(IO::class);
    }

    public function translation()
    {
        return $this->hasOne(Io_types_translation::class);
    }

    public static function getColumns($table, $json=true)
    {
        $sql = DB::raw('SHOW COLUMNS FROM '.$table);

        $type = Io_type::where('table',$table)->first();

        $all = Io_types_translation::all(); // TODO:testing

        $translation = Io_types_translation::where("io_type_id", $type->id)->first();
        $translation = json_decode($translation->fields, true);


        try{
            $columns = Db::select($sql);
            $columns = array_filter($columns, function($element) {
                return $element->Field != "id"
                    && !Str::endsWith($element->Field, "_at")
                    && !Str::endsWith($element->Field, "_id")
                    && $element->Field != "reference"
                    ;
            });


            Log::channel("app")->info("Translation ", [
                "translation" => $translation,
                "columns" => $columns,
            ]);

            if ( $json ) {
                return \response( )->json([
                    "message"=>"found",
                    "data" => $columns,
                    "translation" => $translation
                ]);
            } else {
                return array_values($columns);
            }

        } catch (\Exception $exception) {

            if ( $json ) {
                return \response( )->json([
                "message"=>"nothing found",
                "data" => null
                ], 404);
            } else {
                return $exception;
            }
        }
    }

    public static function getColNames($table){
        $columnsDb = Io_type::getColumns($table, false);
        $tableColumns = [];
        foreach ($columnsDb as $key => $value) $tableColumns[] = $value->Field;
        return $tableColumns;
    }

    public static function rnCol($table, Array $aCol ) {
        Schema::table($table, function (Blueprint $table) use ($aCol){
            $table->renameColumn($aCol[0], $aCol[1]) ;
        });
    }

    public static function addCol($table, $col) {
        Schema::table($table, function (Blueprint $table) use ($col){
            $table->string($col)->nullable();
        });
    }

    public static function rmCol($table, $col) {
        if (Schema::hasColumn($table, $col)) {
            Schema::table($table, function($table) use ($col){
                $table->dropColumn($col);
            });
        }
    }

    public static function getTypeId($table) {
        return (new static)::where('table', $table)->first()->id;
    }

}
