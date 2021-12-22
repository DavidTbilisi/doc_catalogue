<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class Io_type extends Model
{
    use HasFactory;

    public function ios()
    {
        $this->belongsTo(IO::class);
    }


    public static function getColumns($table, $json=true)
    {
        $sql = DB::raw('SHOW COLUMNS FROM '.$table);

        try{
            $columns = Db::select($sql);
            $columns = array_filter($columns, function($element) {
                return $element->Field != "id"
                    && !Str::endsWith($element->Field, "_at")
                    && !Str::endsWith($element->Field, "_id")
                    && $element->Field != "reference"
                    ;
            });
            if ( $json ) {
                return \response( )->json([
                    "message"=>"found",
                    "data" => $columns
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
            $table->string($col);
        });
    }

    public static function rmCol($table, $col) {
        if (Schema::hasColumn($table, $col)) {
            Schema::table($table, function($table) use ($col){
                $table->dropColumn($col);
            });
        }
    }

}
